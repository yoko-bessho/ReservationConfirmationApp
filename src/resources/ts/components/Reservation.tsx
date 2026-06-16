import ReservationList, { type ReservationRow } from './ReservationList';
import { useState, useEffect} from 'react';

function Reservation() {
    const [latestReservations, setLatestReservations]
        = useState<ReservationRow[]>([]);

    //(比較key, Reservation)の形で差分を管理。比較keyはvisitDate_patientId_reservationContentで生成している
    const [addedDiffs, setAddedDiffs]
        = useState<Record<string, ReservationRow>>({});

    const [deletedDiffs, setDeletedDiffs]
        = useState<Record<string, ReservationRow>>({});

    const [latestImportAt, setLatestImportAt]
        = useState<string | null>(null);

    const [previousImportAt, setPreviousImportAt] = useState<string | null>(null);

    const [importDates, setImportDates]
        = useState<string[]>([]);

    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const controller = new AbortController();

        const fetchData = async () => {
            setLoading(true);
            setError(null);

            try {
                const response = await fetch("/api/reservations", {
                    signal: controller.signal,
                });

                if (!response.ok) {
                    throw new Error(
                        `HTTP errorです! status: ${response.status}`,
                    );
                }

                const data = await response.json();

                setLatestImportAt(data.latestImportAt);
                setLatestReservations(data.latestReservations);
                setAddedDiffs(data.addedDiffs);
                setDeletedDiffs(data.deletedDiffs);
                setPreviousImportAt(data.previousImportAt);
                setImportDates(data.importDates);
            } catch (err) {
                if (err instanceof DOMException && err.name === "AbortError") {
                    return;
                } else {
                    setError(
                        err instanceof Error
                            ? err.message
                            : "不明なエラーが発生しました",
                    );
                }
            } finally {
                setLoading(false);
            }
        };
        fetchData();

        return () => {
            controller.abort();
        };
    }, []);

    const latestRows: ReservationRow[] = latestReservations.map(
        (reservation) => ({
            visit_date: reservation.visit_date.slice(0, 10),
            patient_id: reservation.patient_id,
            patient_name: reservation.patient_name,
            reservation_content: reservation.reservation_content,
        }),
    );

    // 差分のオブジェクトのvalueを取り出して配列に変換
    const addedRows: ReservationRow[] = Object.values(addedDiffs);
    const deletedRows: ReservationRow[] = Object.values(deletedDiffs);

    const toJST = (utcString: string | null): string => {
        if (!utcString) return '';
        return new Date(utcString).toLocaleString('ja-JP', {
            timeZone: 'Asia/Tokyo',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            // hour: '2-digit',
            // minute: '2-digit',
        });
    };

    const latestImportDate: string = toJST(latestImportAt);

    return (
        <div className="container">
            <div className="section">
                {loading && <p>データを読み込み中...</p>}
                {error && <p style={{ color: "red" }}>{error}</p>}
                <h2>予約一覧</h2>
                <form>
                    <button className="button" type="submit">
                        エクスポート
                    </button>
                </form>
                <h3>最新インポート日時：{latestImportDate}</h3>
                <p>予約件数： {latestReservations.length}件</p>
                <ReservationList rows={latestRows} />
            </div>
            <div className="section">
                <div>
                    <h3>予約変更状況確認はこちら</h3>
                    <form>
                        <label>比較対象日選択</label>
                        <select className="select-box">
                            {importDates.map((date) => (
                                <option key={date} value={date}>
                                    {toJST(date)}
                                </option>
                            ))}
                        </select>
                        <button className="button">予約変更状況チェック</button>
                    </form>
                </div>
                <div className="diff-list">
                    <h3>追加された予約</h3>
                    <ReservationList rows={addedRows} />
                </div>
                <div className="diff-list">
                    <h3>削除された予約</h3>
                    <ReservationList rows={deletedRows} />
                </div>
            </div>
        </div>
    );
}

export default Reservation;

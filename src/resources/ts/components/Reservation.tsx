import ReservationList, { type ReservationRow } from './ReservationList';

const latestRows: ReservationRow[] = [
    { visitDate: '2023/10/01', patientId: '00112233', patientName: '山田太郎', reservationContent: '定期検診' },
];

const addedRows: ReservationRow[] = [
    { visitDate: '2023/10/01', patientId: '00112233', patientName: '山田太郎', reservationContent: '定期検診' },
];

const deletedRows: ReservationRow[] = [
    { visitDate: '2023/11/11', patientId: '00223344', patientName: '山田二郎', reservationContent: 'インフルエンザ予防接種' },
];

function Reservation() {
    return (
        <div className="container">
            <div className="section">
                <h2>予約一覧</h2>
                <form>
                    <button className="button" type="submit">
                        エクスポート
                    </button>
                </form>
                <h3>最新インポート日時：2023/XX/XX</h3>
                <p>予約件数： X件</p>
                <ReservationList rows={latestRows} />
            </div>
            <div className="section">
                <div>
                    <h3>予約変更状況確認はこちら</h3>
                    <form>
                        <label>比較対象日選択</label>
                        <select className="select-box">
                            <option value="2023/10/01">2023/10/01</option>
                            <option value="2023/10/02">2023/10/02</option>
                            <option value="2023/10/03">2023/10/03</option>
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

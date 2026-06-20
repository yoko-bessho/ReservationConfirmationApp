import Table from './Table';

export type ReservationRow = {
    visit_date: string;
    patient_id: string;
    patient_name: string;
    reservation_content: string;
    isHighlighted?: boolean;
};

type Props = {
    rows: ReservationRow[];
    highlighted?: boolean;
}

function ReservationList({ rows, highlighted = false }: Props) {
    return (
        <Table>
            <thead>
                <tr>
                    <th>予約日</th>
                    <th>患者ID</th>
                    <th>患者名</th>
                    <th>予約内容</th>
                </tr>
            </thead>
            <tbody>
                {rows.map((row) => (
                    <tr
                        key={`${row.patient_id}_${row.visit_date}_${row.reservation_content}`}
                        className={
                            highlighted || row.isHighlighted
                                ? "added-highlighted"
                                : ""
                        }
                    >
                        <td>{row.visit_date}</td>
                        <td>{row.patient_id}</td>
                        <td>{row.patient_name}</td>
                        <td>{row.reservation_content}</td>
                    </tr>
                ))}
            </tbody>
        </Table>
    );
}

export default ReservationList;

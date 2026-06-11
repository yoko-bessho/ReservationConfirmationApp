import Table from './Table';

export type ReservationRow = {
    visitDate: string;
    patientId: string;
    patientName: string;
    reservationContent: string;
}

type Props = {
    rows: ReservationRow[];
}

function ReservationList({ rows }: Props) {
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
                    <tr key={`${row.patientId}-${row.visitDate}`}>
                        <td>{row.visitDate}</td>
                        <td>{row.patientId}</td>
                        <td>{row.patientName}</td>
                        <td>{row.reservationContent}</td>
                    </tr>
                ))}
            </tbody>
        </Table>
    );
}

export default ReservationList;

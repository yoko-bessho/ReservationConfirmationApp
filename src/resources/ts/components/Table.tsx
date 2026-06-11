import type { ReactNode } from "react";

type TableProps = {
    children: ReactNode;
}

function Table({ children }: TableProps) {
    return (
        <table className="table">
            {children}
        </table>
    )
}

export default Table;
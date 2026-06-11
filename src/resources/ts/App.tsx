import { createRoot } from "react-dom/client";
import Reservation from "./components/Reservation";
import "./index.css";


function App() {
    return (
        <>
            <Reservation />
        </>
    );
}

const container = document.getElementById("app");

if (container) {
    if (!(window as any).__reactRoot) {
        (window as any).__reactRoot = createRoot(container);
    }
    (window as any).__reactRoot.render(<App />);
}

export default App;
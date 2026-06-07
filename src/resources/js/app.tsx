import { createRoot } from 'react-dom/client';

function App() {
    return (
        <>
            <h1>Hello React</h1>
            <p>Laravel8 + React19 + Vite</p>
        </>
    );
}

const root = document.getElementById('app');

if (root) {
    createRoot(root).render(<App />);
}
import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';

export default function Dashboard() {
    const { url } = usePage();

    useEffect(() => {
        if (url !== '/app') {
            window.location.href = '/app';
        }
    }, [url]);

    return null;
}

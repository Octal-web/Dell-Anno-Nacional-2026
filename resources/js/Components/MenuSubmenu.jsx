import { useEffect, useRef, useState } from "react";
import { Link, router } from "@inertiajs/react";

export const MenuSubmenu = ({ items, isMenuOpen, isHeaderVisible, menuRef, mobileRoute, onNavigate }) => {
    const [visible, setVisible] = useState(false);
    const [height, setHeight] = useState(0);
    const containerRef = useRef(null);

    useEffect(() => {
        if (isMenuOpen && mobileRoute && window.innerWidth < 768) {
            router.visit(route(mobileRoute));
            return;
        }

        if (isMenuOpen) {
            setVisible(true);
            const frame = requestAnimationFrame(() => {
                setHeight(containerRef.current?.scrollHeight ?? 0);
            });
            return () => cancelAnimationFrame(frame);
        }

        setHeight(0);
        const timeout = setTimeout(() => setVisible(false), 300);
        return () => clearTimeout(timeout);
    }, [isMenuOpen, items.length, mobileRoute]);

    if (!visible && !isMenuOpen) return null;

    return (
        <div
            ref={menuRef}
            className={`${mobileRoute ? "hidden md:block" : "block"} md:fixed md:right-1/2 md:translate-x-1/2 md:top-[102px] 2xl:top-[117px] w-full bg-white overflow-hidden transition-[height,transform] duration-300 ${isHeaderVisible ? "shadow-md" : "-translate-y-full"}`}
            style={{ height: `${height}px` }}
        >
            <div ref={containerRef} className="container max-w-x-large py-4 md:pt-10 md:pb-16">
                <div className="grid grid-cols-2 gap-x-6 md:gap-x-20 gap-y-4 md:gap-y-10 justify-between max-w-[1600px]">
                    {items.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            onClick={onNavigate}
                            className="font-secondary font-light text-black tracking-wide border-b py-2 transition-all hover:text-opacity-70"
                        >
                            {item.nome}
                        </Link>
                    ))}
                </div>
            </div>
        </div>
    );
};

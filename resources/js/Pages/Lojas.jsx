import { router, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";

import DefaultLayout from "@/Layouts/DefaultLayout";

import { ProductsForm } from "@/Components/ProductsForm";
import { StoresList } from "@/Components/StoresList";
import { StoresRegionFilter } from "@/Components/StoresRegionFilter";
import { StoresText } from "@/Components/StoresText";

const getInitialRegion = () => {
    const params = new URLSearchParams(window.location.search);
    return params.get("region") === "internacional" ? "internacional" : "brasil";
};

const Page = () => {
    const { conteudos, lojas: initialStores, chamadaForm } = usePage().props;
    const [selectedRegion, setSelectedRegion] = useState(getInitialRegion);
    const [stores, setStores] = useState(initialStores);
    const [allStores, setAllStores] = useState(initialStores);
    const [loading, setLoading] = useState(true);
    const regionFilterRef = useRef(null);

    const regions = [
        { nome: "Brasil", slug: "brasil" },
        { nome: "Internacional", slug: "internacional" },
    ];

    useEffect(() => {
        setLoading(false);
    }, []);

    useEffect(() => {
        setAllStores(initialStores);
        setStores(initialStores);
    }, [initialStores]);

    const handleRegionChange = (url) => {
        setLoading(true);

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ["lojas"],
            onSuccess: (page) => {
                setStores(page.props.lojas);
                setAllStores(page.props.lojas);
                setLoading(false);
                requestAnimationFrame(() => {
                    if (regionFilterRef.current) {
                        window.scrollTo({
                            top: Math.max(0, window.scrollY + regionFilterRef.current.getBoundingClientRect().top - 80),
                            behavior: "smooth",
                        });
                    }
                });
            },
        });
    };

    const isBrasil = getInitialRegion() === "brasil";

    return (
        <DefaultLayout>
            <StoresText content={conteudos[0]} />

            <StoresRegionFilter
                filterRef={regionFilterRef}
                regions={regions}
                selectedRegion={selectedRegion}
                setSelectedRegion={setSelectedRegion}
                onRegionChange={handleRegionChange}
            />

            <StoresList
                content={conteudos[1]}
                stores={stores}
                allStores={allStores}
                loading={loading}
                setStores={setStores}
                setLoading={setLoading}
                isBrasil={isBrasil}
            />

            <ProductsForm content={chamadaForm} />
        </DefaultLayout>
    );
};

export default Page;

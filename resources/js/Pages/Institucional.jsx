import { usePage } from "@inertiajs/react";

import DefaultLayout from "@/Layouts/DefaultLayout";

import { AboutBanner } from "@/Components/AboutBanner";
import { AboutPainting } from "@/Components/AboutPainting";
import { AboutPractices } from "@/Components/AboutPractices";
import { AboutSteps } from "@/Components/AboutSteps";
import { AboutSustain } from "@/Components/AboutSustain";
import { AboutTimeline } from "@/Components/AboutTimeline";

const Page = () => {
    const { acontecimentos, etapas, imagensGaleria, conteudos } =
        usePage().props;

        return (
        <DefaultLayout>
            <AboutBanner content={conteudos[0]} />

            <AboutSteps steps={etapas} />

            <AboutTimeline slides={acontecimentos} />

            <AboutPainting content={conteudos[6]} />

            <AboutSustain
                content={conteudos[8]}
            />

            <AboutPractices content={conteudos[9]} />
        </DefaultLayout>
    );
};

export default Page;

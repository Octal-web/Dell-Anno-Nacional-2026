import { Head, Link, usePage } from "@inertiajs/react";

const mensagens = {
    403: {
        titulo: "Acesso negado",
        descricao: "Você não tem permissão para acessar esta página.",
    },
    404: {
        titulo: "Página não encontrada",
        descricao: "A página que você procura não existe ou foi removida.",
    },
    500: {
        titulo: "Erro no servidor",
        descricao: "Algo deu errado do nosso lado. Tente novamente em alguns instantes.",
    },
    503: {
        titulo: "Serviço indisponível",
        descricao: "Estamos em manutenção. Voltamos em breve.",
    },
};

const Page = () => {
    const { status } = usePage().props;
    const { titulo, descricao } = mensagens[status] ?? mensagens[500];

    return (
        <>
            <Head title={`${status} - ${titulo} | Dell Anno`}>
                <meta name="robots" content="noindex, nofollow" />
            </Head>

            <main className="min-h-screen flex items-center justify-center py-20">
                <div className="container max-w-small text-center">
                    <img
                        src="/site/img/logo.svg"
                        alt="Dell Anno"
                        className="w-40 mx-auto mb-12 sm:mb-16"
                    />

                    <span className="block text-7xl md:text-8xl 2xl:text-9xl font-light tracking-wide leading-none mb-6 md:mb-8">
                        {status}
                    </span>

                    <h1 className="text-3xl md:text-4xl 2xl:text-[45px] font-light uppercase tracking-wide leading-snug mb-4 md:mb-6">
                        {titulo}
                    </h1>

                    <p className="font-secondary font-light sm:tracking-wide sm:leading-loose mb-10 md:mb-12">
                        {descricao}
                    </p>

                    <Link
                        href="/"
                        className="inline-block font-secondary text-sm uppercase tracking-wide border border-current px-8 py-4 transition-all hover:bg-black hover:text-white"
                    >
                        Voltar para a home
                    </Link>
                </div>
            </main>
        </>
    );
};

export default Page;

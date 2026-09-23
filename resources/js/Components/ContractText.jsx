import LetterReveal from "./LetterReveal";

export const ContractText = ({ content }) => {
    return (
        <section className="pt-16 md:pt-30 2xl:pt-40 pb-4 2xl:pb-40">
            <div className="container max-w-large">
                <LetterReveal className="text-4xl md:text-5xl 2xl:text-[55px] font-light uppercase sm:tracking-wide text-balance text-center mb-6 md:mb-10" text={content.titulo} element="h1" />

                <p className="font-secondary font-light text-justify md:text-center sm:tracking-wide sm:leading-loose whitespace-pre-line max-w-[1000px] mx-auto mb-10">{content.texto}</p>
            </div>
        </section>
    );
};
import LetterReveal from "./LetterReveal";
import { Reveal } from "./Reveal";

export const AboutSustain = ({ content }) => {
    return (
        <section className="pt-16 sm:pt-20 md:pt-24 2xl:pt-32">
            <div className="container max-w-large">
                <div className="grid grid-cols-1 md:grid-cols-2 items-center">
                    <Reveal direction="left">
                        <LetterReveal className='md:text-right text-4xl md:text-5xl 2xl:text-6xl font-secondary mb-5' text={content.titulo} />
                        <img src={content.imagem} className="w-60 md:mb-10 md:ml-auto" />
                    </Reveal>

                    <Reveal
                        direction="right"
                        className="max-md:mt-12 md:pl-16"
                    >
                        <div
                            className="font-secondary font-light max-md:text-justify md:leading-loose md:tracking-wide"
                            dangerouslySetInnerHTML={{
                                __html: content.texto,
                            }}
                        />
                    </Reveal>
                </div>
            </div>
        </section>
    );
};

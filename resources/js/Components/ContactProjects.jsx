import { Reveal } from './Reveal';
import { ContactProjectsSlides } from './ContactProjectsSlides';

export const ContactProjects = ({ projects }) => {
    if (!projects?.length) return;

    return (
        <section className="md:pt-10 pb-10 md:pb-20">
            <div className="container max-w-large">
                <Reveal direction="bottom" scale={true}>
                    <h2 className="text-3xl text-center font-light uppercase sm:tracking-wide sm:leading-snug mb-6 md:mb-12 2xl:mb-16">
                        Confira nossos <strong className="font-semibold">projetos</strong> pelo mundo!
                    </h2>
                </Reveal>

                <ContactProjectsSlides slides={projects} />
            </div>
        </section>
    );
};

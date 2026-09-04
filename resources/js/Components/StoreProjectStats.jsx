export const StoreProjectStats = ({ project }) => {

    return (
        <section className="pt-16">
            <div className="container max-w-large">
                <div className="flex items-center flex-col md:flex-row justify-between">
                    <div className="flex items-center">
                        <span className="font-secondary text-2xl 2xl:text-[32px] font-light">Créditos</span>
                        <span className="font-secondary font-light ml-10">{project.creditos}</span>
                    </div>
                </div>
            </div>
        </section>
    );
};
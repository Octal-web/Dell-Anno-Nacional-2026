export const PostContent = ({ post }) => {
    return (
        <section className="py-16 md:py-24 2xl:py-30">
            <div className="container max-w-medium">
                <div
                    className="
                        [&_img]:h-auto [&_img]:!cursor-auto [&_h1]:text-5xl [&_h1]:my-10 [&_h1]:font-light [&_h1]:uppercase [&_h2]:text-4xl [&_h2]:my-10 [&_h2]:font-light [&_h2]:uppercase [&_h3]:text-2xl [&_h3]:my-10 [&_h3]:font-light [&_h3]:uppercase [&>*:first-child]:!mt-0 [&_p+ul]:mt-2.5 [&_ul+p]:mt-2.5 [&_ul]:list-disc [&_ul]:list-inside
                        [&_table]:w-full

                        [&_table]:block
                        [&_thead]:hidden
                        [&_tbody]:block
                        [&_tr]:block
                        [&_tr]:mb-6
                        [&_td]:block
                        [&_td]:w-full

                        md:[&_table]:w-auto
                        md:[&_table]:table
                        md:[&_thead]:table-header-group
                        md:[&_tbody]:table-row-group
                        md:[&_tr]:table-row
                        md:[&_td]:table-cell

                        md:[&_tr>td:first-child:nth-last-child(2)]:w-1/2
                        md:[&_tr>td:first-child:nth-last-child(2)~td]:w-1/2
                        md:[&_tr>td:first-child:nth-last-child(3)]:w-1/3
                        md:[&_tr>td:first-child:nth-last-child(3)~td]:w-1/3
                        md:[&_table:has(img)]:-mx-8
                        md:[&_table:has(img)]:border-separate
                        md:[&_table:has(img)]:border-spacing-x-8
                    "
                    dangerouslySetInnerHTML={{ __html: post.conteudo }}
                />
            </div>
        </section>
    );
};
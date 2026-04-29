import{r as a,j as t,L as l,u as i}from"./app-D9Gzu4jx.js";import{D as m}from"./DefaultLayout-DSQDtrBm.js";import{g as r,S as o}from"./ScrollTrigger-CUwdeFQJ.js";import{R as d}from"./Reveal-C5Zs0VPV.js";const c=({post:s})=>{const e=a.useRef(null);return a.useEffect(()=>{r.registerPlugin(o),r.fromTo(e.current,{backgroundPositionY:"100%"},{backgroundPositionY:"0%",duration:1,ease:"none",scrollTrigger:{trigger:e.current,start:"top bottom",end:"bottom top",scrub:!0}})},[]),t.jsxs("section",{ref:e,className:"relative max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]",style:{backgroundImage:`url(${s.banner})`},children:[t.jsx("div",{className:"absolute inset-0 bg-black/70"}),t.jsx("div",{className:"relative container max-w-large",children:t.jsx("div",{className:"h-[380px] flex items-end",children:t.jsx("h2",{className:"text-3xl md:text-4xl 2xl:text-[45px] text-white font-light sm:leading-tight uppercase max-w-5xl mb-10",children:s.titulo})})})]})},x=({post:s})=>t.jsx("section",{className:"py-16 md:py-24 2xl:py-30",children:t.jsx("div",{className:"container max-w-medium",children:t.jsx("div",{className:`
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
                    `,dangerouslySetInnerHTML:{__html:s.conteudo}})})}),h=({posts:s})=>t.jsxs(t.Fragment,{children:[t.jsx("section",{className:"pt-10 md:pt-20 pb-10",children:t.jsx("div",{className:"container max-w-large",children:t.jsxs("div",{className:"flex items-center justify-between gap-10 md:gap-20",children:[t.jsx("h3",{className:"text-xl md:text-2xl 2xl:text-[30px] font-light uppercase tracking-wide leading-snug whitespace-nowrap",children:"Saiba mais"}),t.jsx(l,{href:route("Blog.index"),className:"mt-auto md:mx-4 border border-neutral-800 bg-white max-sm:text-sm font-light text-center uppercase max-sm:tracking-tight py-2 px-2 sm:px-4 md:px-8 sm:min-w-44 transition-all hover:bg-black hover:text-white",children:"Todos os Posts"})]})})}),t.jsx("section",{className:"pt-2 md:pt-10 pb-20 md:pb-30 2xl:pb-44",children:t.jsx("div",{className:"container max-w-large",children:t.jsx("div",{className:"grid grid-cols-2 md:grid-cols-3 gap-x-3 md:gap-x-8 max-md:gap-y-14",children:s.map((e,n)=>t.jsxs(d,{direction:"bottom",scale:!0,className:"group flex flex-col",children:[t.jsx(l,{href:route("Blog.post",{slug:e.slug}),className:"overflow-hidden aspect-[5/4] md:aspect-[13/9]",children:t.jsx("img",{src:e.imagem,className:"w-full h-full object-cover transition-all duration-500 group-hover:scale-110",alt:e.titulo})}),t.jsx("div",{className:"font-secondary text-neutral-500 max-sm:text-xs max-md:text-sm font-light mt-2 md:mt-6",children:e.data}),t.jsx(l,{href:route("Blog.post",{slug:e.slug}),className:"block my-4 md:my-6 transition-all hover:opacity-70",children:t.jsx("h3",{className:"text-lg sm:text-xl md:text-2xl 2xl:text-[26px] min-h-12 sm:min-h-16 font-light max-sm:leading-tight max-w-sm line-clamp-2 mb-auto",children:e.titulo})}),t.jsx("p",{className:"font-secondary max-sm:text-sm font-light sm:tracking-wide line-clamp-4 sm:line-clamp-5 max-w-md mb-5 md:mb-8 2xl:mb-10",children:e.previa}),t.jsx(l,{href:route("Blog.post",{slug:e.slug}),className:"mt-auto mr-auto border border-neutral-800 bg-white font-light text-center uppercase py-1.5 px-3 md:p-2 sm:min-w-44 transition-all hover:bg-black hover:text-white",children:"Leia mais"})]},n))})})})]}),f=()=>{const{post:s,posts:e}=i().props;return t.jsxs(m,{children:[t.jsx(c,{post:s}),t.jsx(x,{post:s}),t.jsx(h,{posts:e})]})};export{f as default};

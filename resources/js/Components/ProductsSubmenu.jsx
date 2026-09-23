import { usePage } from "@inertiajs/react";
import { MenuSubmenu } from "./MenuSubmenu";

export const ProductsSubmenu = (props) => {
    const { produtosMenu } = usePage().props;

    return (
        <MenuSubmenu
            {...props}
            mobileRoute="Produtos.index"
            items={produtosMenu.map((produto) => ({
                nome: produto.nome,
                href: route("Produtos.produto", { slug: produto.slug }),
            }))}
        />
    );
};

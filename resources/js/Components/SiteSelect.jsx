import Select, { components } from 'react-select';

// Só a lista de opções fica fora do Lenis, para rolar internamente;
// o restante do select continua com o scroll suave da página.
const MenuList = (props) => (
    <components.MenuList {...props} innerProps={{ ...props.innerProps, 'data-lenis-prevent': true }} />
);

export const SiteSelect = ({ components: customComponents, ...props }) => (
    <Select
        menuPlacement="auto"
        menuShouldScrollIntoView={false}
        {...props}
        components={{ MenuList, ...customComponents }}
    />
);

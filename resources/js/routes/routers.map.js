import Usuarios from "../page/Usuarios";
import Perfis from "../page/Perfis";
import Aparelhos from "../page/Aparelhos";

export default [
    {
        path: '/',
        redirect: "/usuarios"
    },
    {
        path: "/usuarios",
        component: Usuarios
    },
    {
        path: "/perfis",
        component: Perfis
    },
    {
        path: "/aparelhos",
        component: Aparelhos
    }
];
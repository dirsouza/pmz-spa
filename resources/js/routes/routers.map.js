import Usuarios from "../page/Usuarios";
import Perfis from "../page/Perfis";

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
    }
];
import Usuarios from "../page/Usuarios";
// import Usuarios from './components/Usuarios';
// import Perfis from './components/Perfis';
// import Aparelhos from './components/Aparelhos';

export default [
    {
        path: '/',
        redirect: "/usuarios"
    },
    {
        path: "/usuarios",
        component: Usuarios,
        // children: [
        //     {
        //
        //     }
        // ]
    }
];
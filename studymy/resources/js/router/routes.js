import AuthPage from "../components/pages/AuthPage.vue";
import ReportErrorPage from "../components/pages/ReportErrorPage.vue";
import MainPage from "../components/pages/MainPage.vue";
import ProfilePage from "../components/pages/ProfilePage.vue";
import SubjectPage from "../components/pages/SubjectPage.vue";
import DetailSubjectPage from "../components/pages/DetailSubjectPage.vue";
import StudyMainPage from "../components/pages/StudyMainPage.vue";
import GroupsPage from "../components/pages/GroupsPage.vue";
import StudyGroupsPage from "../components/pages/StudyGroupsPage.vue";
import DetailGroupPage from "../components/pages/DetailGroupPage.vue";
import DetailGroupStudentsPage from "../components/pages/DetailGroupStudentsPage.vue";
import ProfileUserPage from "../components/pages/ProfileUserPage.vue";
import AddUserPage from "../components/pages/AddUserPage.vue";
import DetailGroupSubjectsPage from "../components/pages/DetailGroupSubjectsPage.vue";
import UsersPage from "../components/pages/UsersPage.vue";
import CreateTimetablePage from "../components/pages/CreateTimetablePage.vue";
import StoreErrorsPage from "../components/pages/StoreErrorsPage.vue";
import AddSubjectPage from "../components/pages/AddSubjectPage.vue";
import AddGroupPage from "../components/pages/AddGroupPage.vue";


const routes = [
    {
        path: '/', 
        component: AuthPage,
        name: 'Login',
        meta: { requiresGuest: true }
    },
    {
        path: '/report-error', 
        component: ReportErrorPage,
        name: 'ReportError',
        // meta: { requiresAuth: true }
    },
    {
        path: '/store-errors', 
        component: StoreErrorsPage,
        name: 'Errors',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    },
    {
        path: '/main', 
        component: MainPage,
        name: 'Main',
        meta: { requiresAuth: true }
    },
    {
        path: '/profile', 
        component: ProfilePage,
        name: 'Profile',
        meta: { requiresAuth: true }
    },
    {
        path: '/subject/:id', 
        component: SubjectPage,
        name: 'Subject',
        meta: { requiresAuth: true }
    },
    {
        path: '/detail-subject/:id', 
        component: DetailSubjectPage,
        name: 'DetailSubject',
        meta: { requiresAuth: true }
    },
    {
        path: '/study-main', 
        component: StudyMainPage,
        name: 'StudyMain',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/groups', 
        component: GroupsPage,
        name: 'Groups',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/groups/study-groups', 
        component: StudyGroupsPage,
        name: 'StudyGroups',
        meta: { requiresAuth: true , allowedRoles: ['Преподаватель', 'Модератор']}
    },
    {
        path: '/groups/study-groups/:id', 
        component: DetailGroupPage,
        name: 'DetailGroup',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/groups/study-groups/:id/students', 
        component: DetailGroupStudentsPage,
        name: 'DetailGroupStudents',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/profile/:id', 
        component: ProfileUserPage,
        name: 'ProfileUser',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/add-user', 
        component: AddUserPage,
        name: 'AddUser',
        meta: { requiresAuth: true,allowedRoles: [ 'Модератор'] }
    },
    {
        path: '/groups/study-groups/:id/subjects', 
        component: DetailGroupSubjectsPage,
        name: 'DetailGroupSubjects',
        meta: { requiresAuth: true, allowedRoles: ['Преподаватель', 'Модератор'] }
    },
    {
        path: '/users', 
        component: UsersPage,
        name: 'Users',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    },
    {
        path: '/timetable/create', 
        component: CreateTimetablePage,
        name: 'CreateTimetable',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    },
    {
        path: '/add-subject', 
        component: AddSubjectPage,
        name: 'AddSubject',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    },
    {
        path: '/add-subject/:id', 
        component: AddSubjectPage,
        name: 'UpdateSubject',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    },
    {
        path: '/add-group', 
        component: AddGroupPage,
        name: 'AddGroup',
        meta: { requiresAuth: true, allowedRoles: ['Модератор'] }
    }
];


export default routes;
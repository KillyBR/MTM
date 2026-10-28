import { Routes } from '@angular/router';

export const routes: Routes = [
    { path: '', loadComponent: () => import('./components/page/page').then( p => p.Page) },
    { path: '**', redirectTo: '' }
];

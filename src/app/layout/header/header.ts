import { Component, inject, signal, PLATFORM_ID, ElementRef, HostListener } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { BreakpointObserver } from '@angular/cdk/layout';
import { RouterLink } from '@angular/router';
import { console } from 'inspector';

@Component({
    selector: 'app-header',
    standalone: true,
    imports: [CommonModule, RouterLink],
    templateUrl: './header.html',
    styleUrl: './header.scss',
})
export class Header {
    private bpo = inject(BreakpointObserver);
    private pid = inject(PLATFORM_ID);
    private elementRef = inject(ElementRef);

    isMobile = signal<boolean>(false);
    isMenuOpen = signal<boolean>(false);

    constructor() {
        if (isPlatformBrowser(this.pid)) {
            this.bpo.observe('(max-width: 576px)').subscribe(res => {
                this.isMobile.set(res.matches);
                if (!res.matches) {
                    // this.isMenuOpen.set(false);
                    this.toggleMenu();
                }
                else{
                    this.isMenuOpen.set(false);
                }
            });
        }

    }

    toggleMenu(event?: Event): void {
        if (event) {
            event.stopPropagation();
        }
        this.isMenuOpen.update(prev => !prev);
    }

    @HostListener('document:click', ['$event'])
    onDocumentClick(event: MouseEvent): void {
        // Solo actúa si estamos en móvil y el menú está abierto
        if (this.isMobile() && this.isMenuOpen()) {
            const clickedInside = this.elementRef.nativeElement.contains(event.target);
            if (!clickedInside) {
                this.isMenuOpen.set(false);
            }
        }
    }


    closeMenu(): void {
        this.isMenuOpen.set(false);
        // console.log("si lo llama");
    }
}

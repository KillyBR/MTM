import { Component, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { MensajeService } from '../../services/mensaje-service';
import { email, form, FormField, required } from '@angular/forms/signals';
import { NgxTurnstileComponent } from 'ngx-turnstile';

@Component({
    selector: 'app-page',
    imports: [RouterLink, FormField, NgxTurnstileComponent],
    templateUrl: './page.html',
    styleUrl: './page.scss',
})

export class Page {
    msjSrv = inject(MensajeService);
    msjResp = signal<string | null>(null);

    readonly siteKey = '0x4AAAAAAEuakxt3hPTRJ2VK';
    readonly turnstileToken = signal<string | null>(null);

    onTurnstileResolve(token: string | null){
        this.turnstileToken.set(token);
    }

    
    datos = null;

    msjModel = {
        nombre: '',
        email: '',
        telefono: '',
        motivo: ''
    }

    msjForm = form(signal(this.msjModel), (fieldPath) => {
        required(fieldPath.nombre, {message: 'Favor de indicar el nombre'});
        required(fieldPath.email, {message: 'Favor de indicar el email'});
        required(fieldPath.telefono, {message: 'Favor de indicar el teléfono'});
        required(fieldPath.motivo, {message: 'Favor de completar la información'});

    });


    isFormValid(): boolean {
        const val = this.msjForm().value();
        
        console.log("el valor de es " + (this.turnstileToken() != null));
        
        return (val.email.includes('@') && (this.turnstileToken() != null)) && !this.msjForm().invalid();
    }

    enviarMsj(): void {
        
        if (this.msjForm().invalid() && this.isFormValid()){
            this.msjForm().markAsTouched();
            return;
        }
        const val = this.msjForm().value();

        this.msjSrv.enviarMsj(val).subscribe({
            next: r => {
                if(r.success){
                    this.msjResp.set('Mensaje enviado');
                    this.msjForm().reset(this.msjModel);
                }
                else
                    alert("Error " + r.msg);                            
            },
            error: e=> { alert(e.error); }
        });
        
        setInterval(() => {
            this.msjResp.set(null);
        }, 5000);

    }
}

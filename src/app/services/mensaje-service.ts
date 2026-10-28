import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
    providedIn: 'root',
})
export class MensajeService {
    private url = 'http://localhost/MTM/' + 'api/?url=Mensajes/';
    private headers = { 'Content-Type': 'application/json' };

    constructor( private httpc: HttpClient) { }

    enviarMsj(obj:any){
        // return this.httpc.post<Resp>(`${this.url}Enviar`, obj, { headers: this.headers }); 
        return this.httpc.post<Resp>(`${this.url}Enviar`, obj, { headers: this.headers }); 
    }

}

export interface Resp {
  success:boolean,
  total:number,
  list:any[] | null,
  msg:string,
  trtbl:number | undefined
}

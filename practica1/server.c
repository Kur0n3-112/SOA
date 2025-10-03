/* server.c — Servidor de calculadora en C
 * Compilar: cc server.c -o server
 * Ejecutar: ./server
 */
#include <arpa/inet.h>
#include <netinet/in.h>
#include <unistd.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

double sumar(double a, double b) {
    return a + b;
}//fin funcion sumar

double restar(double a, double b) {
    return a - b;
}//fin funcion restar

double dividir(double a, double b) {
    return a / b;
}//fin funcion dividir

double multiplicar(double a, double b) {
    return a * b;
}//fin funcion multiplicar

int main(){
  int s = socket(AF_INET, SOCK_STREAM, 0);
  int opt=1; setsockopt(s,SOL_SOCKET,SO_REUSEADDR,&opt,sizeof(opt));
  struct sockaddr_in a={0};
  a.sin_family=AF_INET; a.sin_port=htons(12345); a.sin_addr.s_addr=INADDR_ANY;
  bind(s,(struct sockaddr*)&a,sizeof(a)); listen(s,5);
  printf("Servidor C en 12345\n");
  while(1){
    int c = accept(s,NULL,NULL);
    char buf[1024]={0}; int n=read(c,buf,sizeof(buf)-1);
    if(n>0){
      char op[16]; double x,y;
      //Dependiendo del tipo de operacion que se realize el servidor la procesa y devuelve una respuesta al cliente.
      if(sscanf(buf,"%s %lf %lf",op,&x,&y)==3 && strcasecmp(op,"SUM")==0){
        double r=sumar(x,y);
        char out[128]; snprintf(out,sizeof(out),"OK[SUM]: %f\n",r);
        write(c,out,strlen(out));
      }//fin if
      else if(sscanf(buf,"%s %lf %lf",op,&x,&y)==3 && strcasecmp(op,"RES")==0){
        double r=restar(x,y);
        char out[128]; snprintf(out,sizeof(out),"OK[RES]: %f\n",r);
        write(c,out,strlen(out));
      }//fin else if
      else if(sscanf(buf,"%s %lf %lf",op,&x,&y)==3 && strcasecmp(op,"DIV")==0){
        double r=dividir(x,y);
        char out[128]; snprintf(out,sizeof(out),"OK[DIV]: %f\n",r);
        write(c,out,strlen(out));
    }//fin else if
      else if(sscanf(buf,"%s %lf %lf",op,&x,&y)==3 && strcasecmp(op,"MUL")==0){
        double r=multiplicar(x,y);
        char out[128]; snprintf(out,sizeof(out),"OK[MUL]: %f\n",r);
        write(c,out,strlen(out));
    }//fin else if
    else {
        write(c,"ERR InvalidFormat\n",19);
      }//fin else
    }//fin if
    close(c);
  }//fin while
  close(s); return 0;
}//fin funcion main

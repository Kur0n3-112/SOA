/* client.c — Cliente de calculadora en C
 * Compilar: cc client.c -o client
 * Ejecutar: ./client
 */
#include <arpa/inet.h>
#include <netinet/in.h>
#include <unistd.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h> //Introducida para el uso de malloc para asignar espacio

int main(int argc, char *argv[]) {
  //Comprueba que al menos un elemento se ha introducido al ejecutar el programa.
  if (argc < 4) {
      printf("ERROR: Faltan valores a operar o especificar el tipo de operacion.\nUSO: %s SUM(sumar),RES(restar),DIV(division),MUL(multiplicacion) <1er numero> <2do numero> \n", argv[0]);
      return 1;
  }//fin if
  else if (argc >= 5) {
      printf("ERROR: Hay demasiados valores a operar.\nUSO: %s SUM(sumar),RES(restar),DIV(division),MUL(multiplicacion) <1er numero> <2do numero> \n", argv[0]);
      return 1;
  }//fin if

  int s=socket(AF_INET,SOCK_STREAM,0);
  struct sockaddr_in addr={0};
  addr.sin_family=AF_INET; addr.sin_port=htons(12345);
  inet_pton(AF_INET,"127.0.0.1",&addr.sin_addr);
  connect(s,(struct sockaddr*)&addr,sizeof(addr));

  //Junta los valores de argv los cuales son introducidos por el usuario desde la consola a la hora de ejecutar el programa y el tipo de operacion que se realiza, al juntarlo luego escribe en la variabe msg el mensaje que se va a enviar al servidor, esto se realiza de la misma forma en todos los tips de operaciones siguientes (sumar, restar, multiplicar y dividir).
  if (strcmp(argv[1], "SUM") == 0) {
      char *msg = (char *)malloc(50 * sizeof(char));
      sprintf(msg, "SUM %s %s\n", argv[2], argv[3]);
      write(s,msg,strlen(msg));
  }//fin if
  else if (strcmp(argv[1], "RES") == 0) {
      char *msg = (char *)malloc(50 * sizeof(char));
      sprintf(msg, "RES %s %s\n", argv[2], argv[3]);
      write(s,msg,strlen(msg));
  }//fin else if
  else if (strcmp(argv[1], "DIV") == 0) {
      char *msg = (char *)malloc(50 * sizeof(char));
      sprintf(msg, "DIV %s %s\n", argv[2], argv[3]);
      write(s,msg,strlen(msg));
  }//fin else if
  else if (strcmp(argv[1], "MUL") == 0) {
      char *msg = (char *)malloc(50 * sizeof(char));
      sprintf(msg, "MUL %s %s\n", argv[2], argv[3]);
      write(s,msg,strlen(msg));
  }//fin else if
  else {
      printf("Opción Incorrecta: %s\n", argv[1]);
  }//fin else
  char buf[1024]; int n=read(s,buf,sizeof(buf)-1);
  buf[n]=0; printf("Respuesta: %s\n",buf);
  close(s); return 0;
}//fin funcion main

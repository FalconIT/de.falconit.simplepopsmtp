/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package simplepop;

import java.io.BufferedReader;
import java.io.Console;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.net.ConnectException;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Scanner;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author di34pam
 */
public class SimplePOP {
    Scanner c = new Scanner(System.in);
    PrintStream outputStream;
    BufferedReader inputStream;
    Socket mailSocket = new Socket();
    InetAddress serverAddress = InetAddress.getLoopbackAddress();
    String responseLine;
    StringBuilder serverResponse = new StringBuilder();
    int portnumber;
    String username;
    String password;
    int addHost(String host) {
        try {
                serverAddress=InetAddress.getByName(host);
                return 1;
        } catch (UnknownHostException e) {
            return 0;        
        }
    }
    private void getCredentials() {
        String inputUser, inputPass;
        Console con = System.console();
        
        while (true) {
            System.out.print("\nBenutzername: ");
            inputUser=c.next();
            if(!inputUser.isEmpty()) {
                System.out.print("\nPasswort: ");
                inputPass=String.valueOf(con.readPassword());
                if(!inputPass.isEmpty()) {
                    username=inputUser;
                    password=inputPass;
                    break;
                }
                else {
                    System.out.println("Passwort darf nicht leer sein!");
                }
            }
            else {
                System.out.println("Benutzername darf nicht leer sein!");
            }
            
        }
        
    }
    
    int con() {
    
            try {
                mailSocket = new Socket();
                mailSocket.connect(new InetSocketAddress(serverAddress, portnumber), 5000);
                System.out.println(mailSocket.isConnected());
                outputStream = new PrintStream(mailSocket.getOutputStream());
                inputStream=new BufferedReader(new InputStreamReader(mailSocket.getInputStream()));
                inputStream.readLine();   
                outputStream.println("USER "+username);
                outputStream.println("PASS "+password);
                if(!inputStream.readLine().contains("+OK")) { // read first line of login procedure
                    //System.out.println("ERROR FIRST");
                    return 0;
                } 
                if(!inputStream.readLine().contains("+OK")) { // read second line
                    //System.out.println("ERROR SECOND");
                    return 0;
                } 
                return 1;    // usr and pass ok, connected.
            }
            catch (ConnectException conex) {
                //System.out.println("conex");
                return 2;   // 
            }
            catch (IOException i) {
                //System.out.println("kaputt!");
                i.printStackTrace();
                return 0;    // something went really wrong
            }
    }
    String getMails()  {
        try {
            outputStream.println("NOOP");
            inputStream.readLine();
            //System.out.println("NOOP OK");
        }
        catch (Exception ex) {
            while(!(con()==1)) {
                System.out.println("Falscher Benutzername oder falsches Passwort!");
                getCredentials();
            }
        }
        outputStream.println("LIST");
        serverResponse.setLength(0);
        int msgCounter = 0;
        while (true) {
            try {
                responseLine=inputStream.readLine();
                if(responseLine.equals(".")) {
                    break;
                }
                serverResponse.append(responseLine).append("\n");
                msgCounter+=1;
            }
            catch (IOException i) {
                System.out.println("debug me");
                break;
            }
        }
        return serverResponse.toString();
    }
    String getMailByID(int id) {
        try {
            outputStream.println("NOOP");
            inputStream.readLine();
        }
        catch (Exception ex) {
        while(!(con()==1)) {
            System.out.println("Falscher Benutzername oder falsches Passwort!");
            getCredentials();
           }
        }
        responseLine="";
        outputStream.println("RETR "+id);
        /* try {Thread.sleep(5000); } catch (InterruptedException ie) {} */
        serverResponse.setLength(0);
        try { responseLine = inputStream.readLine(); } catch (IOException i) {}
        if(!responseLine.contains("+OK")) { return "Error: Mail-ID not present";}
        while (true) {
            try {
                if(responseLine.equals(".")) {
                    break;
                }                
                responseLine=inputStream.readLine();
                serverResponse.append(responseLine).append("\n");

            }
            catch (IOException i) {
                System.out.println("debug me");
                break;
            }
        }

        return serverResponse.toString();
        
    }
    void discon() {
        try {
           mailSocket.close();
        } catch (IOException ex) {
            Logger.getLogger(SimplePOP.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    /**
     * @param args the command line arguments
     */
    @SuppressWarnings("empty-statement")
    void run() {
                discon();
        String inputServer;

        
        while (true) {
            System.out.print("\n Serveraddresse: ");
            inputServer=c.next();
            if(addHost(inputServer)==1) { break; } 
            System.out.println("\n Error: Unknown host or Timeout");
        }
        System.out.print("\nerfolgreich: ");
        System.out.println(serverAddress.toString());
        while (true) {
            try {
                System.out.print("\n Port: ");
                portnumber = Integer.parseInt(c.next());
                if(portnumber<=65535 && portnumber>0) {
                    break;
                }
            }
            catch (NumberFormatException n) {
                System.out.println("\nFehler: keine Zahl!");
            }
        }
        System.out.print("\ngewaehlter port: ");
        System.out.println(portnumber);
        getCredentials();
        
        
        
//STATE-MACHINE
        while (true) {   // outer loop
            System.out.print("\nAktion wählen! 1: Nachrichten auflisten | 2: Zeige Nachricht an (nach ID) | 4: Programm beenden\n > ");
            int action;
            while (true) {   // inner loop: mode-select
                try {        // input & check for type==int
                    action = Integer.parseInt(c.next());
                    break;                
                }
                catch (NumberFormatException n) {
                    System.out.println("NaN");
                                                    }
                }
            switch (action)
            {
                /* SWITCH  CASES */
                case 1: { // list mails
                    System.out.println(getMails());
                    break;
                }

                case 2: { // read mail by id
                    int chosenMail;
                    while (true) { // input & check for type==int
                                try {
                                    System.out.print("\n Mail-ID (0 = abbrechen): ");
                                    chosenMail = Integer.parseInt(c.next());
                                    break;
                                }
                                catch (NumberFormatException n) {
                                    System.out.println("\nFehler: keine Zahl!");
                                }
                            }
                    if (chosenMail==0) { break; }
                    System.out.println(getMailByID(chosenMail));  // call getMailByID & print
                    break;
                }

                case 4: {
                    if(!mailSocket.isClosed()) { discon(); }
                    return; // quit!
                }
                default: {
                    System.out.println("\nunbekannte Wahl");
                    break;
                }
            }            
        }
    }
    public static void main(String[] args) {
        SimplePOP go = new SimplePOP();
        go.run();
    }
}

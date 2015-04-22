/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package simplesmtp;

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
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import sun.misc.BASE64Encoder;

/**
 *
 * @author di34pam
 */
public class simpleSMTP {
    Console c = System.console();  
    PrintStream outputStream;
    BufferedReader inputStream;
    Socket mailSocket = new Socket();
    InetAddress serverAddress = InetAddress.getLoopbackAddress();
    String responseLine, pass64,inputUser;
    StringBuilder serverResponse = new StringBuilder();
    int portnumber;
    String username;
    String password;
    Calendar cal = Calendar.getInstance();
    Date date;
    DateFormat timestamp = new SimpleDateFormat("dd.MM.yyyy HH:mm:ss"); 
    String getHost() {
        String hostname;
    try {
        hostname = java.net.InetAddress.getLocalHost().getHostName();
    } catch (UnknownHostException uhe) {
        hostname ="default";
    }
    return hostname;
    }
    int addHost(String host) {
        try {
                serverAddress=InetAddress.getByName(host);
                return 1;
        } catch (UnknownHostException e) {
            return 0;        
        }
    }
    private void getCredentials() {
        String inputPass;

        while (true) {
            System.out.print("\nBenutzername: ");
            inputUser=c.readLine();
            if(!inputUser.isEmpty()) {
                System.out.print("\nPasswort: ");
                inputPass=String.valueOf(c.readPassword());
                if(!inputPass.isEmpty()) {
                    pass64 = new BASE64Encoder().encode(("\0"+inputUser+"\0"+inputPass).getBytes());
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
                //System.out.println(serverAddress.getHostAddress()+" "+portnumber);
                mailSocket.connect(new InetSocketAddress(serverAddress, portnumber), 5000);
                //System.out.println(mailSocket.isConnected());
                outputStream = new PrintStream(mailSocket.getOutputStream());
                outputStream.println("EHLO "+getHost());
                
                inputStream=new BufferedReader(new InputStreamReader(mailSocket.getInputStream()));
               
                outputStream.println("AUTH PLAIN "+pass64);
                // String readLine = inputStream.readLine();
                // System.out.println(readLine);
                if(true) { // read first line of login procedure
                    //System.out.println("ERROR FIRST");
                    return 1;
                } 
                return 1;    // usr and pass ok, connected.
            }
            catch (ConnectException conex) {
                System.out.println("conex");
                return 2;   // 
            }
            catch (IOException i) {
                //System.out.println("kaputt!");
                i.printStackTrace();
                return 0;    // something went really wrong
            }
    }
    void discon() {
        try {
           mailSocket.close();
        } catch (IOException ex) {
            Logger.getLogger(simpleSMTP.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    /**
     * @param args the command line arguments
     */
    @SuppressWarnings("empty-statement")
    void run() {
                discon();
        String inputServer;
            date = cal.getTime();
            System.out.println(timestamp.format(date));
        
        while (true) {
            System.out.print("\n Serveraddresse: ");
            inputServer=c.readLine();
            if(addHost(inputServer)==1) { break; } 
            System.out.println("\n Error: Unknown host or Timeout");
        }
        System.out.print("\nerfolgreich: ");
        System.out.println(serverAddress.toString());
        while (true) {
            try {
                System.out.print("\n Port: ");
                portnumber = Integer.parseInt(c.readLine());
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
            System.out.print("\nAktion wählen! 1: Mail schreiben | 4: Programm beenden\n > ");
            int action;
            while (true) {   // inner loop: mode-select
                try {        // input & check for type==int
                    action = Integer.parseInt(c.readLine());
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
                    composeMail();
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
        simpleSMTP go = new simpleSMTP();
        go.run();
    }

    private void composeMail() {
        List<String> recipient = new ArrayList<String>();
        String tmprcp="";
        
        while (true) {
            System.out.println("Bitte Empfänger angeben oder mit :ok bestätigen");
            tmprcp=c.readLine();
            if(tmprcp.equals(":ok") && !recipient.isEmpty()) {
                break;
            }
            else if(!tmprcp.equals(":ok") && !tmprcp.equals("")) {
                recipient.add(tmprcp);
            }
            tmprcp="";
        }
        String[] recipientArray = new String[recipient.size()];
        recipientArray = recipient.toArray(recipientArray);
        String subject ="";
        System.out.println("Betreff:");
        subject = c.readLine();
        List<String> message = new ArrayList<String>();
        String tmpmsg="";
        System.out.println("Message: ");
        while (true) {
            tmpmsg=c.readLine();
            if(tmpmsg.equals(":ok") && !message.isEmpty()) {
                // System.out.println("KKKKKKKKK");
                break;
            }
            else if(!tmpmsg.equals(":ok")) {
                if(tmpmsg.equals(".")) {
                    tmpmsg="..";
                }
                message.add(tmpmsg);
            }
            tmpmsg="";
        }
        String[] messageArray = new String[message.size()];
        messageArray = message.toArray(messageArray);
        try {
            outputStream.println("NOOP");
            inputStream.readLine();
        }
        catch (Exception ex) {
            while(!(con()==1)) {
                System.out.println("Falscher Benutzername oder falsches Passwort!");
            }
        }
        try {
            outputStream.println("MAIL FROM:<"+inputUser+">");
            for(int i=0;i<recipientArray.length;i++) {
                outputStream.println("RCPT TO:<"+recipientArray[i].toString()+">");
            }
            outputStream.println("DATA");
            outputStream.println("From: <"+inputUser+">");
            for(int i=0;i<recipientArray.length;i++) {
                outputStream.println("To: <"+recipientArray[i].toString()+">");
            }
            outputStream.println("Subject: "+subject);
            outputStream.println("Date: "+timestamp.format(date));
            outputStream.println("");
            for(int i=0;i<messageArray.length;i++) {
                outputStream.println(messageArray[i]);
                
            }
            outputStream.println(".");
            outputStream.println("QUIT");
        } catch (Exception ex) {
            ex.printStackTrace();
        }
         
    }
}

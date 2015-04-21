/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package testguiprojekt;

import java.awt.Point;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.Serializable;
import java.io.UnsupportedEncodingException;
import java.text.DateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;
import java.util.Properties;
import javax.mail.*;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import javax.swing.JOptionPane;
import javax.swing.table.DefaultTableModel;

/**
 *
 * @author FalconIT
 */
class SerializableMail implements Serializable {

    private final int id;
    private final String from, to, subject, content;
    private final Date date;
    private boolean read;

    boolean getRead() {
        return read;
    }

    void setRead() {
        read = true;
    }

    String getFrom() {
        return from;
    }

    String getSubject() {
        return subject;
    }

    Date getDate() {
        return date;
    }

    String getContent() {
        return content;
    }

    SerializableMail(Message msg, int id_) {

        id = id_;
        String to_, from_, subject_, content_;
        Date date_;
        //from = msg.getFrom().toString();
        // ArrayList<Address> receivers = new ArrayList<>(Arrays.asList(mime.getRecipients(Message.RecipientType.TO)));
        try {
            try {
                from_ = msg.getFrom()[0].toString();
                to_ = msg.getRecipients(Message.RecipientType.TO)[0].toString();
            } catch (MessagingException ex) {
                from_ = "";
                to_ = "illegal character";
            }
            subject_ = msg.getSubject();
            try {
                content_ = (String) msg.getContent();
            } catch (ClassCastException ex) {
                content_ = "MULTIPART";
            }
            try {
                date_ = msg.getSentDate();
            } catch (NullPointerException ex) {
                date_ = null;
            }
        } catch (IOException | MessagingException ex) {

// JOptionPane.showMessageDialog(null, "Fehler beim DL: local storage " + ex.getMessage());
            to_ = "";
            from_ = "";
            subject_ = "";
            content_ = "";
            date_ = null;
        }
        to = to_;
        subject = subject_;
        date = date_;
        content = content_;
        read = false;
        from = from_;
    }
}

public class MainMenuFrame extends javax.swing.JFrame {

    DateFormat df = DateFormat.getDateInstance(DateFormat.LONG, Locale.GERMANY);
    Session mailSession = loadSession();
    Folder popFolder = null;
    ArrayList<SerializableMail> mailStorageArrayList = new ArrayList<>();
    private int MAILCOUNT;
    private void flushCompose() {
        receiverField.setText("");
        ccField.setText("");
        bccField.setText("");
        subjectField.setText("");
        messageBody.setText("");
    }
    private void updateTable() {
        DefaultTableModel inboxTableModel = (DefaultTableModel) inboxTable.getModel();
        inboxTableModel.setRowCount(0);
        SerializableMail tmp;
        for (int i = 0; i < mailStorageArrayList.size(); i++) {
            tmp = (SerializableMail) mailStorageArrayList.get(i);
            inboxTableModel.addRow(new Object[]{tmp.getRead(), tmp.getFrom(), tmp.getSubject(), tmp.getDate()});
        }
        //  inboxTableModel.addRow();
    }

    private ArrayList<SerializableMail> loadLocalStorage() {
        ArrayList<SerializableMail> local = new ArrayList<>();
        try {
            File file = new File("mailstorage.dat");
            if (file.exists()) {
                FileInputStream loadFile = new FileInputStream(file);
                ObjectInputStream loadObj = new ObjectInputStream(loadFile);
                local = (ArrayList<SerializableMail>) loadObj.readObject();
            }
        } catch (java.io.IOException | ClassNotFoundException ex) {
            JOptionPane.showMessageDialog(null, "Fehler beim Speichern: local storage " + ex.getMessage());
        }
        try {
            MAILCOUNT = local.size();
        } catch (NullPointerException ex) {
            MAILCOUNT = 0;
        }
        return local;
    }

    private void saveLocalStorage(ArrayList<SerializableMail> local) {
        File oldSave = new File("mailstorage.dat");
        oldSave.delete();
        oldSave = null;
        try {
            File file = new File("mailstorage.dat");
            FileOutputStream saveFile = new FileOutputStream(file);
            ObjectOutputStream saveObj = new ObjectOutputStream(saveFile);
            saveObj.writeObject(local);
            saveFile.close();
        } catch (IOException ex) {
            JOptionPane.showMessageDialog(null, "Fehler beim Laden: local storage " + ex.getMessage());
        }
    }

    private Folder popFolder()
            throws MessagingException {
        
        Store popStore = loadSession().getStore("pop3");
        popStore.connect();
        Folder popFolder = popStore.getFolder("INBOX");
        popFolder.open(Folder.READ_ONLY);
        System.out.println(popStore.hashCode());
        return popFolder;
    }

    private Session loadSession() {
        Session storedSession = null;
        try {
            File file = new File("account.dat");
            if (file.exists()) {
                FileInputStream loadFile = new FileInputStream(file);
                final Properties storedProperties = new Properties();
                storedProperties.load(loadFile);
                loadFile.close();
                Authenticator passAuth = new Authenticator() {
                    @Override
                    protected PasswordAuthentication getPasswordAuthentication() {
                        return new PasswordAuthentication(storedProperties.getProperty("mail.pop3.user"), storedProperties.getProperty("mail.pop3.password"));
                    }
                };
                storedSession = Session.getInstance(storedProperties, passAuth);
            }
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(null, "Debug " + ex.getMessage());
        }
        return storedSession;
    }

    private boolean sessionNULL() {
        return (mailSession == null);
    }



    private void setGlobalSession(Session global) {
        mailSession = global;
        File oldSave = new File("account.dat");
        oldSave.delete();
        oldSave = null;
        try {
            File file = new File("account.dat");
            FileOutputStream saveFile = new FileOutputStream(file);
            global.getProperties().store(saveFile, "");
        } catch (IOException ex) {
            JOptionPane.showMessageDialog(null, "sack " + ex.getMessage());
        }
    }

    /**
     * Creates new form NewJFrame
     */
    public MainMenuFrame() {
        initComponents();

    }

    private void setVis(boolean vis) {
        this.setVisible(vis);
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        optionsFrame = new javax.swing.JFrame();
        jLabel1 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        addressField = new javax.swing.JTextField();
        nameField = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        jLabel5 = new javax.swing.JLabel();
        popServer = new javax.swing.JTextField();
        smtpServer = new javax.swing.JTextField();
        passwordField = new javax.swing.JPasswordField();
        jLabel6 = new javax.swing.JLabel();
        jLabel7 = new javax.swing.JLabel();
        popPort = new javax.swing.JTextField();
        smtpPort = new javax.swing.JTextField();
        jButton1 = new javax.swing.JButton();
        submitSettings = new javax.swing.JButton();
        composeFrame = new javax.swing.JFrame();
        jLabel8 = new javax.swing.JLabel();
        receiverField = new javax.swing.JTextField();
        jLabel9 = new javax.swing.JLabel();
        jLabel10 = new javax.swing.JLabel();
        ccField = new javax.swing.JTextField();
        bccField = new javax.swing.JTextField();
        jLabel11 = new javax.swing.JLabel();
        subjectField = new javax.swing.JTextField();
        jScrollPane3 = new javax.swing.JScrollPane();
        messageBody = new javax.swing.JTextArea();
        sendButton = new javax.swing.JButton();
        abortButton = new javax.swing.JButton();
        jSplitPane1 = new javax.swing.JSplitPane();
        jScrollPane1 = new javax.swing.JScrollPane();
        inboxTable = new javax.swing.JTable();
        jScrollPane2 = new javax.swing.JScrollPane();
        jTextArea1 = new javax.swing.JTextArea();
        jButton2 = new javax.swing.JButton();
        jButton3 = new javax.swing.JButton();
        jMenuBar1 = new javax.swing.JMenuBar();
        jMenu1 = new javax.swing.JMenu();
        jMenuItem1 = new javax.swing.JMenuItem();
        jMenuItem2 = new javax.swing.JMenuItem();

        optionsFrame.setTitle("Optionen");
        optionsFrame.setMinimumSize(new java.awt.Dimension(378, 265));
        optionsFrame.setResizable(false);
        optionsFrame.setType(java.awt.Window.Type.POPUP);
        optionsFrame.addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowActivated(java.awt.event.WindowEvent evt) {
                optionsFrameWindowActivated(evt);
            }
        });
        optionsFrame.addComponentListener(new java.awt.event.ComponentAdapter() {
            public void componentHidden(java.awt.event.ComponentEvent evt) {
                optionsFrameComponentHidden(evt);
            }
        });

        jLabel1.setText("Ihr Name");

        jLabel2.setText("eMail Addresse");

        jLabel3.setText("POP3 Server / Port");

        jLabel4.setText("SMTP Server / Port");

        jLabel5.setText("Passwort");

        passwordField.addKeyListener(new java.awt.event.KeyAdapter() {
            public void keyTyped(java.awt.event.KeyEvent evt) {
                passwordFieldKeyTyped(evt);
            }
        });

        jLabel6.setText(":");

        jLabel7.setText(":");

        jButton1.setText("Abbrechen");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        submitSettings.setText("Einstellungen testen & speichern");
        submitSettings.setEnabled(false);
        submitSettings.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                submitSettingsActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout optionsFrameLayout = new javax.swing.GroupLayout(optionsFrame.getContentPane());
        optionsFrame.getContentPane().setLayout(optionsFrameLayout);
        optionsFrameLayout.setHorizontalGroup(
            optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(optionsFrameLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(optionsFrameLayout.createSequentialGroup()
                        .addComponent(jButton1)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(submitSettings))
                    .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                        .addGroup(optionsFrameLayout.createSequentialGroup()
                            .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                .addComponent(jLabel1)
                                .addComponent(jLabel2))
                            .addGap(29, 29, 29)
                            .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                .addComponent(nameField, javax.swing.GroupLayout.PREFERRED_SIZE, 217, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addComponent(addressField, javax.swing.GroupLayout.PREFERRED_SIZE, 217, javax.swing.GroupLayout.PREFERRED_SIZE)))
                        .addGroup(optionsFrameLayout.createSequentialGroup()
                            .addComponent(jLabel3)
                            .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                            .addComponent(popServer, javax.swing.GroupLayout.PREFERRED_SIZE, 138, javax.swing.GroupLayout.PREFERRED_SIZE)
                            .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                            .addComponent(jLabel6)
                            .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                            .addComponent(popPort)))
                    .addGroup(optionsFrameLayout.createSequentialGroup()
                        .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel4)
                            .addComponent(jLabel5))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addGroup(optionsFrameLayout.createSequentialGroup()
                                .addComponent(smtpServer, javax.swing.GroupLayout.PREFERRED_SIZE, 137, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(jLabel7)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(smtpPort, javax.swing.GroupLayout.PREFERRED_SIZE, 68, javax.swing.GroupLayout.PREFERRED_SIZE))
                            .addComponent(passwordField))))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        optionsFrameLayout.setVerticalGroup(
            optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(optionsFrameLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel1)
                    .addComponent(nameField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(addressField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel3)
                    .addComponent(popServer, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel6)
                    .addComponent(popPort, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel4)
                    .addComponent(smtpServer, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel7)
                    .addComponent(smtpPort, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel5)
                    .addComponent(passwordField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(optionsFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton1)
                    .addComponent(submitSettings))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        composeFrame.setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        composeFrame.setMinimumSize(new java.awt.Dimension(640, 480));
        composeFrame.setResizable(false);

        jLabel8.setText("An:");

        receiverField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                receiverFieldActionPerformed(evt);
            }
        });

        jLabel9.setText("CC:");

        jLabel10.setText("BCC:");

        jLabel11.setText("Betreff:");

        messageBody.setColumns(20);
        messageBody.setRows(5);
        jScrollPane3.setViewportView(messageBody);

        sendButton.setText("Senden");
        sendButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                sendButtonActionPerformed(evt);
            }
        });

        abortButton.setText("Abbrechen");
        abortButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                abortButtonActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout composeFrameLayout = new javax.swing.GroupLayout(composeFrame.getContentPane());
        composeFrame.getContentPane().setLayout(composeFrameLayout);
        composeFrameLayout.setHorizontalGroup(
            composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(composeFrameLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(composeFrameLayout.createSequentialGroup()
                        .addComponent(jScrollPane3)
                        .addContainerGap())
                    .addGroup(composeFrameLayout.createSequentialGroup()
                        .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel9)
                            .addComponent(jLabel8)
                            .addComponent(jLabel10))
                        .addGap(18, 18, 18)
                        .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(receiverField)
                            .addComponent(ccField, javax.swing.GroupLayout.Alignment.TRAILING)
                            .addComponent(bccField, javax.swing.GroupLayout.Alignment.TRAILING))
                        .addGap(9, 9, 9))
                    .addGroup(composeFrameLayout.createSequentialGroup()
                        .addComponent(jLabel11)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(subjectField)
                        .addContainerGap())))
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, composeFrameLayout.createSequentialGroup()
                .addContainerGap(587, Short.MAX_VALUE)
                .addComponent(abortButton)
                .addGap(33, 33, 33)
                .addComponent(sendButton)
                .addContainerGap())
        );
        composeFrameLayout.setVerticalGroup(
            composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(composeFrameLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel8, javax.swing.GroupLayout.PREFERRED_SIZE, 24, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(receiverField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel9, javax.swing.GroupLayout.PREFERRED_SIZE, 24, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(ccField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel10, javax.swing.GroupLayout.PREFERRED_SIZE, 24, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(bccField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel11, javax.swing.GroupLayout.PREFERRED_SIZE, 24, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(subjectField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 346, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(composeFrameLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(sendButton)
                    .addComponent(abortButton))
                .addContainerGap())
        );

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("simpleJavaMail [Client PÜPI]");
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        jSplitPane1.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);
        jSplitPane1.setResizeWeight(0.4);

        inboxTable.setAutoCreateRowSorter(true);
        inboxTable.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {

            },
            new String [] {
                "G", "Absender", "Betreff", "Datum"
            }
        ) {
            Class[] types = new Class [] {
                java.lang.Boolean.class, java.lang.String.class, java.lang.String.class, java.util.Date.class
            };
            boolean[] canEdit = new boolean [] {
                false, false, false, false
            };

            public Class getColumnClass(int columnIndex) {
                return types [columnIndex];
            }

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit [columnIndex];
            }
        });
        inboxTable.setGridColor(new java.awt.Color(255, 255, 255));
        inboxTable.setMinimumSize(new java.awt.Dimension(5, 0));
        inboxTable.setRowMargin(0);
        inboxTable.setSelectionMode(javax.swing.ListSelectionModel.SINGLE_SELECTION);
        inboxTable.setShowHorizontalLines(false);
        inboxTable.setShowVerticalLines(false);
        inboxTable.getTableHeader().setReorderingAllowed(false);
        inboxTable.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                inboxTableMouseClicked(evt);
            }
        });
        jScrollPane1.setViewportView(inboxTable);
        inboxTable.getColumnModel().getSelectionModel().setSelectionMode(javax.swing.ListSelectionModel.SINGLE_SELECTION);
        if (inboxTable.getColumnModel().getColumnCount() > 0) {
            inboxTable.getColumnModel().getColumn(0).setMinWidth(20);
            inboxTable.getColumnModel().getColumn(0).setPreferredWidth(20);
            inboxTable.getColumnModel().getColumn(0).setMaxWidth(20);
        }

        jSplitPane1.setTopComponent(jScrollPane1);

        jScrollPane2.setHorizontalScrollBarPolicy(javax.swing.ScrollPaneConstants.HORIZONTAL_SCROLLBAR_NEVER);

        jTextArea1.setEditable(false);
        jTextArea1.setColumns(20);
        jTextArea1.setLineWrap(true);
        jTextArea1.setRows(5);
        jTextArea1.setWrapStyleWord(true);
        jScrollPane2.setViewportView(jTextArea1);

        jSplitPane1.setBottomComponent(jScrollPane2);

        jButton2.setText("Antworten");
        jButton2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton2ActionPerformed(evt);
            }
        });

        jButton3.setText("Neue Mail");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });

        jMenu1.setText("File");

        jMenuItem1.setText("Optionen");
        jMenuItem1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jMenuItem1ActionPerformed(evt);
            }
        });
        jMenu1.add(jMenuItem1);

        jMenuItem2.setText("Mails herunterladen");
        jMenuItem2.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jMenuItem2ActionPerformed(evt);
            }
        });
        jMenu1.add(jMenuItem2);

        jMenuBar1.add(jMenu1);

        setJMenuBar(jMenuBar1);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jSplitPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 747, Short.MAX_VALUE)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, layout.createSequentialGroup()
                        .addGap(0, 0, Short.MAX_VALUE)
                        .addComponent(jButton3)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jButton2)))
                .addContainerGap())
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jSplitPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 427, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jButton2)
                    .addComponent(jButton3))
                .addContainerGap())
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void jMenuItem1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jMenuItem1ActionPerformed
        // TODO add your handling code here:
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                optionsFrame.setVisible(true);
                setVis(false);
            }
        });
    }//GEN-LAST:event_jMenuItem1ActionPerformed

    private void optionsFrameComponentHidden(java.awt.event.ComponentEvent evt) {//GEN-FIRST:event_optionsFrameComponentHidden
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                setVis(true);
            }
        });
    }//GEN-LAST:event_optionsFrameComponentHidden

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                optionsFrame.setVisible(false);
                setVis(false);
            }
        });
    }//GEN-LAST:event_jButton1ActionPerformed

    private void submitSettingsActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_submitSettingsActionPerformed
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                final Properties sessionProperties = new Properties() {
                    {
                        setProperty("fullname", nameField.getText());
                        setProperty("mail.smtp.host", smtpServer.getText());
                        setProperty("mail.smtp.port", String.valueOf(smtpPort.getText()));
                        setProperty("mail.smtp.user", addressField.getText());
                        setProperty("mail.smtp.password", new String(passwordField.getPassword()));
                        setProperty("mail.smtp.auth", "true");
                        setProperty("mail.pop3.host", popServer.getText());
                        setProperty("mail.pop3.port", String.valueOf(popPort.getText()));
                        setProperty("mail.pop3.user", addressField.getText());
                        setProperty("mail.pop3.password", new String(passwordField.getPassword()));
                        setProperty("mail.pop3.auth", "true");
                    }
                };
                Authenticator passAuth = new Authenticator() {
                    @Override
                    protected PasswordAuthentication getPasswordAuthentication() {
                        return new PasswordAuthentication(addressField.getText(), new String(passwordField.getPassword()));
                    }
                };
                Session testing = Session.getInstance(sessionProperties, passAuth);
                Transport transport;
                try {
                    Transport smtpTransport = testing.getTransport("smtp");
                    smtpTransport.connect();
                    smtpTransport.close();
                } catch (MessagingException ex) {
                    JOptionPane.showMessageDialog(null, "SMTP: " + ex.getMessage());
                    return;
                }
                Store store;
                try {
                    store = testing.getStore("pop3");
                    store.connect();
                    store.close();
                } catch (MessagingException ex) {
                    JOptionPane.showMessageDialog(null, "POP3: " + ex.getMessage());
                }
                setGlobalSession(testing);
                optionsFrame.setVisible(false);
                setVis(true);
            }
        });
    }//GEN-LAST:event_submitSettingsActionPerformed

    private void optionsFrameWindowActivated(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_optionsFrameWindowActivated
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                submitSettings.setEnabled(false);
                if (mailSession != null) {
                    nameField.setText(mailSession.getProperty("fullname"));
                    addressField.setText(mailSession.getProperty("mail.pop3.user"));
                    popPort.setText(mailSession.getProperty("mail.pop3.port"));
                    popServer.setText(mailSession.getProperty("mail.pop3.host"));
                    smtpPort.setText(mailSession.getProperty("mail.smtp.port"));
                    smtpServer.setText(mailSession.getProperty("mail.smtp.host"));
                }
            }
        }
        );
    }//GEN-LAST:event_optionsFrameWindowActivated

    private void passwordFieldKeyTyped(java.awt.event.KeyEvent evt) {//GEN-FIRST:event_passwordFieldKeyTyped
        submitSettings.setEnabled(true);
    }//GEN-LAST:event_passwordFieldKeyTyped

    private void jMenuItem2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jMenuItem2ActionPerformed
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                if (popFolder == null || !popFolder.isOpen()) {
                    try {
                        popFolder = popFolder();
                    } catch (MessagingException ex) {
                        JOptionPane.showMessageDialog(null, "Fehler, popFolder: " + ex.getMessage());
                        return;
                    }
                }
                
                try {
                    int messageCount = popFolder.getMessageCount();
                    if (messageCount < MAILCOUNT) {
                        JOptionPane.showMessageDialog(null, "Postfach desynchronisiert, lokale Datenbank wird erneuert.");
                        mailStorageArrayList.clear();
                        MAILCOUNT = 0;
                    }
                    if (messageCount == MAILCOUNT) {
                        JOptionPane.showMessageDialog(null, "Keine neuen Mails.");
                        updateTable();
                        
                    } else {
                        Message[] newmails = popFolder.getMessages(MAILCOUNT + 1, messageCount);
                        SerializableMail tmp;
                        for (int i = 0; i < messageCount - MAILCOUNT; i++) {
                            //   try {
                            tmp = new SerializableMail(newmails[i], MAILCOUNT + 1 + i);
                            mailStorageArrayList.add(tmp);
                    //    }
                            //    catch (Exception ex) {
                            //    JOptionPane.showMessageDialog(null, "Fehler beim Konvertieren: " + ex.getMessage());
                            //}
                        }
                        saveLocalStorage(mailStorageArrayList);
                        JOptionPane.showMessageDialog(null, messageCount - MAILCOUNT + " neue eMails heruntergeladen und lokal gespeichert.");
                        MAILCOUNT = messageCount;
                        updateTable();
                    }
                        popFolder.close(false);
                        popFolder.getStore().close();
                } catch (MessagingException ex) {
                    JOptionPane.showMessageDialog(null, "Fehler, popDownloadr: " + ex.getMessage());
                }
            }
        }
        );
    }//GEN-LAST:event_jMenuItem2ActionPerformed

    private void inboxTableMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_inboxTableMouseClicked
        if (evt.getButton() != 0b1) {
            return;
        }
        int row = inboxTable.rowAtPoint(new Point(5, evt.getY()));
        int real_row = inboxTable.convertRowIndexToModel(row);
        jTextArea1.setText(mailStorageArrayList.get(real_row).getContent());
        jTextArea1.setCaretPosition(0);
        if ((boolean) inboxTable.getModel().getValueAt(real_row, 0) == false) {
            inboxTable.getModel().setValueAt(true, real_row, 0);
            mailStorageArrayList.get(real_row).setRead();
        }
    }//GEN-LAST:event_inboxTableMouseClicked

    private void receiverFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_receiverFieldActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_receiverFieldActionPerformed

    private void jButton2ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton2ActionPerformed
        composeFrame.setVisible(true);
        flushCompose();
        receiverField.setText(mailStorageArrayList.get(inboxTable.convertRowIndexToModel(inboxTable.getSelectedRow())).getFrom());
        subjectField.setText("Re: " + mailStorageArrayList.get(inboxTable.convertRowIndexToModel(inboxTable.getSelectedRow())).getSubject());
    }//GEN-LAST:event_jButton2ActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        saveLocalStorage(mailStorageArrayList);
        try {
            mailSession.getStore().close();
        } catch (MessagingException ex) {

        }

    }//GEN-LAST:event_formWindowClosing

    private void sendButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_sendButtonActionPerformed
        // verify Input
        Message newMail = new MimeMessage(mailSession);
        try {
            newMail.addRecipients(Message.RecipientType.TO, InternetAddress.parse(receiverField.getText()));
        } catch (MessagingException ex) {
            JOptionPane.showMessageDialog(null, "Fehler, Empfänger: " + ex.getMessage());
            return;
        }
        try {
            newMail.addRecipients(Message.RecipientType.CC, InternetAddress.parse(ccField.getText()));
        } catch (MessagingException ex) {
            JOptionPane.showMessageDialog(null, "Fehler, CC: " + ex.getMessage());
        }
        try {
            newMail.addRecipients(Message.RecipientType.BCC, InternetAddress.parse(bccField.getText()));
        } catch (MessagingException ex) {
            JOptionPane.showMessageDialog(null, "Fehler, BCC: " + ex.getMessage());
        }
        try {
            if (subjectField.getText().equals("")) {
                newMail.setSubject("no subject");
            }
            else {
                newMail.setSubject(subjectField.getText());
            }
            newMail.setFrom(new InternetAddress(mailSession.getProperty("mail.smtp.user"), mailSession.getProperty("fullname")));
            newMail.setText(messageBody.getText());
        }   catch (MessagingException | UnsupportedEncodingException ex) {}
        try {
            Transport.send(newMail);
            composeFrame.setVisible(false);
            composeFrame.dispose();
        } catch (MessagingException ex) {
            JOptionPane.showMessageDialog(null, "Schwerwiegender Versandfehler: " + ex.getMessage());
        }

    }//GEN-LAST:event_sendButtonActionPerformed

    private void abortButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_abortButtonActionPerformed
        composeFrame.setVisible(false);
        composeFrame.dispose();
    }//GEN-LAST:event_abortButtonActionPerformed

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        composeFrame.setVisible(true);
        flushCompose();        
    }//GEN-LAST:event_jButton3ActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(MainMenuFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(MainMenuFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(MainMenuFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(MainMenuFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            @Override
            public void run() {
                MainMenuFrame mmf = new MainMenuFrame();
                if (mmf.sessionNULL()) {
                    mmf.optionsFrame.setVisible(true);
                } else {
                    mmf.setVisible(true);
                }
                mmf.mailStorageArrayList = mmf.loadLocalStorage();
                mmf.updateTable();

            }
        });

    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton abortButton;
    private javax.swing.JTextField addressField;
    private javax.swing.JTextField bccField;
    private javax.swing.JTextField ccField;
    private javax.swing.JFrame composeFrame;
    private javax.swing.JTable inboxTable;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton2;
    private javax.swing.JButton jButton3;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel10;
    private javax.swing.JLabel jLabel11;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JLabel jLabel9;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JMenuBar jMenuBar1;
    private javax.swing.JMenuItem jMenuItem1;
    private javax.swing.JMenuItem jMenuItem2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JSplitPane jSplitPane1;
    private javax.swing.JTextArea jTextArea1;
    private javax.swing.JTextArea messageBody;
    private javax.swing.JTextField nameField;
    private javax.swing.JFrame optionsFrame;
    private javax.swing.JPasswordField passwordField;
    private javax.swing.JTextField popPort;
    private javax.swing.JTextField popServer;
    private javax.swing.JTextField receiverField;
    private javax.swing.JButton sendButton;
    private javax.swing.JTextField smtpPort;
    private javax.swing.JTextField smtpServer;
    private javax.swing.JTextField subjectField;
    private javax.swing.JButton submitSettings;
    // End of variables declaration//GEN-END:variables
}

# Proiect_Web_ANS

Nume echipa: ANS-TheGradeSlayers

# Componenta echipa:

    Martinescu Nicolae
    Cobaschi Emanuel Aser
    Nastasiu Stefan

# Structura fisiere

    docs/ - Toata documentatia + styles pentru docs + swagger
    install/ - Fisierele necesare instalarii aplicatiei
    public/ - Continutul public al aplicatiei
        public/assets - Continutul static al aplicatiei
            public/assets/csv - CSV-urile folosite in aplicatie
            public/assets/img - Imaginile folosite in aplicatie
            public/assets/templates - Template-urile pentru email-uri
        public/css - CSS-ul folosit in aplicatie
        public/html - HTML-ul folosit in aplicatie
        public/js - JavaScript-ul folosit in aplicatie
        public/scss - SCSS-ul folosit in aplicatie
        public/sql - Comenzile sql de rulat
        public/router.php - fisierul care face rutarea request-urilor
    /tests - Testele aplicatiei   
    /app - Fisierele ce contin logica aplcatiei
        app/controllers - controlerele aplicatiei
        app/utils - functii ajutatoare pentru backend

# Instalare

    Instalati XAMPP de la link-ul si aveti grija sa fie salvat direct in disk-ul C, nu in vreun alt fisier. De obicei, este
    setat sa se salveze automat la adresa "C:\xampp", dar totusi aveti grija la acest aspect. In fisierul de la adresa "
    C/xampp/htdocs" faceti un "git clone" pentru a salva local proiectul. Faceti backup la fisierul "C:
    /xampp/apache/conf/httpd.conf" si apoi stergeti-l. In folderul install al proiectului, copiati fisierul "httpd.conf" si il puneti in locul fisierului sters.
    Dupa, faceti backup si stergeti fisierul php.ini din "C:/xampp/php" si copiati fisierul "php.ini" din
    folderul install al proiectului si il puneti in locul fisierului sters. Rulati comenzile "composer install" 
    si "npm install" in folderul proiectului. Asigurati-va ca aveti instalat composer si npm pe masina locala. Rulati comenzile sql din fisierul /public/sql/databaseCreation.sql in phpmyadmin sau phpStorm pentru crearea tabelelor. Acum, deschideti un browser (de preferat nu Edge) si accesati "localhost". Ar trebui ca acum sa aveti acces la proiectul nostru, si sa interactionati cu interfata noastra.

Vizualizarea documentatiei sa face prin deschiderea manuala a acestuia la http://localhost/docs/scholarHTML.html.
\
Vizualizarea documentatiei swagger se face la http://localhost/docs/swagger/
\
Video-ul de prezentare se afla in folderul docs.

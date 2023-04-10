# Proiect_Web_ANS

Nume echipa: ANS-TheGradeSlayers

# Componenta echipa:

    Martinescu Nicolae
    Cobaschi Emanuel Aser
    Nastasiu Stefan

# Structura fisiere

    docs/ - Toata documentatia + styles pentru docs + diagrama use-case
    public/ - Continutul public al aplicatiei
        public/assets - Continutul static al aplicatiei
            public/assets/css - CSS-ul folosit in aplicatie
            public/assets/img - Imaginile folosite in aplicatie
            public/assets/js - JavaScript-ul folosit in aplicatie
    app/ - Fisierele ce contin logica aplcatiei
        app/controllers - TBD
        app/models - TBD
        app/views - Paginile concrete ale aplicatiei care vor fi servite avand continut dinamic
            app/views/pages - Paginile necomune ale aplicatiei
            app/views/shared - Pagini care se pot repeta

# Instalare

Instalati XAMPP de la link-ul si aveti grija sa fie salvat direct in disk-ul C, nu in vreun alt fisier. De obicei, este setat sa se salveze automat la adresa "C:\xampp", dar totusi aveti grija la acest aspect. In fisierul de la adresa "C/xampp/htdocs" faceti un "git clone" pentru a salva local proiectul. Deschideti fisierul text de la adresa "xampp/apache/conf/httpd". Cautati "DocumentRoot" (folosind ctrl+f) si schimbati-i adresa cu "C:/xampp/htdocs/Proiect_Web_ANS/public", trebuie schimbat inapoi la valoarea default dupa verificarea/utilizarea aplicatiei, daca nu vrei sa-ti strici mediul si sa nu mearga lucrul/vizualizarea altor proiecte folosind XAMPP. Acum, deschideti un browser (de preferat nu Edge) si accesati "localhost". Ar trebui ca acum sa aveti
acces la proiectul nostru, si sa interactionati cu interfata noastra.

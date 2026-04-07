# Setup e Test del Sistema di Login

## Setup Iniziale

### 1. Database
Il database `SchoolTogether` deve essere creato con le tabelle e i dati di seed.

Esegui i seguenti file SQL in ordine:
1. `db/schema.sql` - Crea le tabelle
2. `db/seed.sql` - Popola i dati di seed

Se stai aggiornando uno schema esistente, esegui:
3. `db/update_schema.sql` - Istruzioni di aggiornamento se necessario

### 2. Verifica della configurazione

Assicurati che `bootstrap.php` abbia la corretta configurazione del database:
```php
$dbh = new DatabaseHelper("localhost", "root", "", "SchoolTogether", "3306");
```

Modifica i parametri se il tuo MySQL ha una configurazione diversa.

### 3. File e cartelle necessari

Verifica che questi file esistano:
- ✓ `pages/login.php`
- ✓ `pages/signup.php`
- ✓ `template/login_main.php`
- ✓ `template/signup_main.php`
- ✓ `template/base.php`
- ✓ `ajax/login/api-login.php`
- ✓ `ajax/login/api-logout.php`
- ✓ `ajax/login/api-signup.php`
- ✓ `js/login.js`
- ✓ `js/signup.js`
- ✓ `bootstrap.php` (con funzioni di autenticazione)
- ✓ `db/database.php` (con metodi di autenticazione)

## Test Manuale

### Test 1: Pagina di Login
1. Apri http://localhost/ProgettoWebFrancalanci/pages/login.php
2. Dovresti vedere il form di login
3. Verifica che il JavaScript carica senza errori (apri la console del browser)

### Test 2: Registrazione di un nuovo utente
1. Apri http://localhost/ProgettoWebFrancalanci/pages/signup.php
2. Compila il form con:
   - **Nome**: Mario
   - **Cognome**: Rossi
   - **Email**: mario@example.com
   - **Password**: password123
   - **Conferma Password**: password123
   - **Bio**: Sono uno studente di informatica (opzionale)
3. Clicca "Registrati"
4. Dovresti vedere messaggio "Account creato con successo!"
5. Dovresti essere reindirizzato a login.php

### Test 3: Login con account appena creato
1. Nella pagina di login, compila:
   - **Email**: mario@example.com
   - **Password**: password123
2. Clicca "Accedi"
3. Dovresti vedere messaggio di successo
4. Dovresti essere reindirizzato a index.php
5. Nella navbar dovresti vedere il dropdown del profilo con il tuo nome

### Test 4: Accesso a pagina protetta quando non loggato
1. Fai logout (clicca il bottone nella navbar)
2. Prova ad accedere a una pagina protetta:
   - http://localhost/ProgettoWebFrancalanci/pages/posts.php
3. Dovresti essere reindirizzato a login.php

### Test 5: Accesso a pagina protetta quando loggato
1. Login nuovamente
2. Apri http://localhost/ProgettoWebFrancalanci/pages/posts.php
3. Dovresti vedere il contenuto della pagina

### Test 6: Logout
1. Nella navbar, clicca il dropdown del profilo
2. Clicca il bottone "Logout"
3. Dovresti essere reindirizzato a index.php
4. Nella navbar dovrebbe comparire il bottone "Profilo" (login)

## Test con dati di seed

Se vuoi aggiungere account di test nel database, puoi aggiungere gli INSERT nel file `db/seed.sql`:

```sql
-- Account di test (password: password123)
INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) 
VALUES (
    'test@example.com', 
    'Test', 
    'User', 
    '$2y$10$...',  -- Password hashata di "password123"
    'Account di test',
    'utente'
);
```

Per ottenere il hash della password "password123", esegui questo in PHP:
```php
echo password_hash('password123', PASSWORD_BCRYPT);
```

## Troubleshooting

### Errore: "Errore di connessione al database"
- Verifica che MySQL stia girando
- Verifica i parametri di connessione in bootstrap.php
- Verifica che il database e le tabelle siano state create

### Il login non funziona
- Apri la console del browser (F12) e controlla gli errori JavaScript
- Verifica che i file api-login.php e api-logout.php esistano
- Verifica che le password nel database siano hashate correttamente

### La sessione non persiste
- Verifica che session_start() sia chiamato in bootstrap.php (✓ fatto)
- Verifica che le sessioni PHP siano abilitate nel php.ini
- Controlla che i cookie siano abilitati nel browser

### Login fallisce ma l'account esiste
- Verifica che la password sia stata hashata durante la registrazione
- Verifica che `password_verify()` sia usata correttamente nell'API
- Controlla il database direttamente con MySQL per vedere if la password è hash

## Debugging

### Abilitare il logging (in bootstrap.php e API):
```php
error_log("Debug message: " . json_encode($data));
```

Controlla il file `php_errors.log` in XAMPP:
```
/Applications/XAMPP/xamppfiles/logs/php_error.log
```

### Verificare le sessioni:
```php
<?php
require_once 'bootstrap.php';
var_dump($_SESSION);
?>
```

### Verificare il database:
```sql
-- In MySQL
SELECT email, nome, cognome, tipo FROM Utente;
```

## Security Check

Verifica questi punti di sicurezza:

- ✓ Tutte le password sono hashate con BCRYPT
- ✓ Le credenziali non sono mai loggate
- ✓ L'input è validato e sanitizzato
- ✓ Le sessioni usano gli ID di sessione di PHP
- ✓ Redirect dopo login (POST-redirect-GET pattern)
- ✓ CSRF token non implementato (aggiungilo se necessario con `wp_nonce_field()` o simili)

## Note

- Al primo test, potresti ricevere un errore di database. Assicurati che il file `db/database.php` esista.
- Se usi XAMPP, accertati che MySQL sia avviato prima di testare.
- Se cambi la porta di MySQL, aggiorna il parametro in `bootstrap.php`.

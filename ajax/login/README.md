# Sistema di Login e Registrazione - Documentazione

## Panoramica
Questo sistema implementa un'autenticazione utente completa con gestione delle sessioni, protezione delle pagine e registrazione di nuovi utenti.

## File creati

### API

#### API di Login
- **`api-login.php`** - Autentica gli utenti
  - Metodo: POST
  - Parametri: `email`, `password`
  - Ritorna: JSON con esito del login
  - Crea sessione PHP con i dati dell'utente

#### API di Logout
- **`api-logout.php`** - Effettua il logout
  - Metodo: POST
  - Distrugge la sessione
  - Reindirizza alla home

#### API di Registrazione
- **`api-signup.php`** - Crea un nuovo account
  - Metodo: POST
  - Parametri: `email`, `nome`, `cognome`, `password`, `password_confirm`, `bio` (opzionale)
  - Validazioni:
    - Email valida
    - Nome e cognome non vuoti
    - Password almeno 8 caratteri
    - Le due password devono coincidere
    - Email non deve essere già registrata
  - Ritorna: JSON con esito della registrazione

### JavaScript

- **`js/login.js`** - Gestisce il form di login
  - Invia i dati all'API via AJAX
  - Mostra messaggi di errore/successo
  - Reindirizza all'home al successo

- **`js/signup.js`** - Gestisce il form di registrazione
  - Invia i dati all'API via AJAX
  - Mostra messaggi di errore/successo
  - Reindirizza al login al successo

### Backend

#### bootstrap.php
Aggiunto con funzioni di autenticazione:

```php
isUserLoggedIn(): bool            // Verifica se loggato
requireLogin(): void              // Reindirizza al login se non autenticato
logout(): void                    // Effettua il logout
getLoggedInUser(): ?array         // Ritorna i dati dell'utente loggato
```

#### database.php
Aggiunti metodi:

```php
getUserByEmail(string $email): ?array   // Recupera utente per email
createUser(...)                          // Crea un nuovo utente con password hashata
```

### Template

#### base.php
Navbar aggiornata:
- Mostra dropdown profilo quando loggato
- Mostra bottone login quando non loggato
- Include link a profilo, i miei post, logout

## Utilizzo

### Proteggere una pagina
Aggiungi questa riga subito dopo `require_once '../bootstrap.php'`:

```php
requireLogin();
```

### Pagine protette (attualmente)
- `pages/posts.php`
- `pages/create_post.php`
- `pages/edit_post.php`
- `pages/profile.php`
- `pages/my_posts.php`
- `pages/joined_posts.php`
- `pages/requests.php`
- `pages/post_info.php`

### Pagine pubbliche
- `index.php`
- `pages/login.php`
- `pages/signup.php`
- `pages/about_us.php`
- `pages/contacts.php`

## Flusso di autenticazione

### Login
1. Utente visita `pages/login.php`
2. Compila form con email e password
3. JavaScript invia POST a `ajax/login/api-login.php`
4. API verifica credenziali nel database
5. Se valide: crea sessione, ritorna JSON con `success: true`
6. Se non valide: ritorna `success: false` con messaggio di errore

### Registrazione
1. Utente visita `pages/signup.php`
2. Compila form con:
   - Nome, cognome, email
   - Password (almeno 8 caratteri)
   - Conferma password
   - Bio opzionale
3. JavaScript invia POST a `ajax/login/api-signup.php`
4. API valida i dati
5. Se validi: crea account, ritorna `success: true`
6. Se non validi: ritorna errori di validazione
7. JavaScript reindirizza al login

### Logout
1. Utente clicca logout nel dropdown del profilo
2. Form POST a `ajax/login/api-logout.php`
3. API distrugge la sessione
4. Reindirizza alla home

## Variabili di sessione

Una volta loggato, la sessione contiene:
```php
$_SESSION['logged_in']       // bool: true/false
$_SESSION['user_email']      // string: email
$_SESSION['user_name']       // string: nome
$_SESSION['user_surname']    // string: cognome
$_SESSION['user_type']       // string: 'utente' o 'admin'
```

## Accesso ai dati dell'utente

### Nel file PHP:
```php
// Verifica se loggato
if (isUserLoggedIn()) {
    $user = getLoggedInUser();
    echo $user['name'];
    echo $user['email'];
}
```

### Nel template:
```php
<?php if (isUserLoggedIn()): ?>
    <p>Benvenuto, <?php echo getLoggedInUser()['name']; ?></p>
<?php else: ?>
    <a href="login.php">Accedi</a>
<?php endif; ?>
```

## Sicurezza

- Le password sono hashate con `password_hash()` (BCRYPT)
- Verifica con `password_verify()`
- Le credenziali non vengono mai logate o esposte
- Input sanitizzato con `filter_var()` e `trim()`
- Sessioni PHP gestite automaticamente

## Configurazione

Nel file `bootstrap.php`, configurare i parametri del database:
```php
$dbh = new DatabaseHelper(
    "localhost",      // host
    "root",           // username
    "",               // password
    "SchoolTogether", // database
    "3306"            // port
);
```

Se questi parametri cambiano, aggiornare il `bootstrap.php`.

## Test

Per testare il sistema:

1. **Registrazione**: Vai a `/pages/signup.php` e crea un account
2. **Login**: Vai a `/pages/login.php` e accedi
3. **Protezione**: Accedi a una pagina protetta senza essere loggato - dovrebbe reindirizzare al login
4. **Logout**: Clicca il bottone logout nel dropdown del profilo

## Note

- I campi email devono essere unici (ogni account ha una sola email)
- La password non è mai mantenuta in sessione (solo l'email e i dati pubblici)
- Se un utente elimina il suo account, i dati vengono anonimizzati (vedi database.php per i trigger)


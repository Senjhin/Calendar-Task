# Instrukcja uruchomienia projektu InterviewTask

## 1. Wymagania wstępne

- Zainstaluj 
  - XAMPP (Apache, MySQL, PHP)
  - Git
  - Node.js (npm)
  - Composer (PHP dependency manager)

  - **Uwaga:** Node.js oraz Git muszą być dodane do zmiennych środowiskowych PATH

## 2. Konfiguracja środowiska XAMPP

- Uruchom Panel Kontrolny XAMPP
- Włącz usługi:
  - Apache
  - MySQL
- Wejdź do katalogu `htdocs` (domyślnie `C:\xampp\htdocs`)

## 3. Tworzenie bazy danych w phpMyAdmin
- Otwórz przeglądarkę i wejdź na adres: http://localhost/phpmyadmin

- Zaloguj się, jeśli wymagane (domyślnie użytkownik: root, bez hasła)

- Kliknij zakładkę Bazy danych

- W polu Utwórz bazę danych wpisz nazwę: todo_app

- Wybierz kodowanie, np. utf8mb4_unicode_ci

- Kliknij Utwórz

- Baza todo_app jest gotowa do użycia

## 4. Klonowanie repozytorium

Otwórz powershell jako administrator i uruchom projekt:

  cd C:\xampp\htdocs
  git clone https://github.com/Senjhin/GrupaRBR-Interview
  cd GrupaRBR-Interview
  composer install
  npm install
  npm run build
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  php artisan view:clear
  php artisan config:cache
  php artisan migrate --seed
  php artisan serve


## 4. Otwórz w przeglądarce 
- Strona będzie dostępna pod adresem http://localhost:8000/
- Zaloguj się na konto które otrzymało seedy:
      - Email: `Mateuszkordysinvestments@gmail.com`
      - Hasło: `Haslo123`
      
- Aby wysłać przypomnienie odnośnie terminu zadania: 
    php artisan reminders:send

**Uwaga:** Polecenie należy uruchomić w głównym katalogu projektu


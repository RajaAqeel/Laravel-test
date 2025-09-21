# Laravel Test Assessment – Hybrid Mediaworks

This repository contains the implementation for the **Laravel Test Assessment**.  
All feature requirements were implemented and verified with PHPUnit.

---

## 📌 Project Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/adeellaradev/Laravel-test
   cd Laravel-test
   ````
2. **Install dependencies**
    ````bash
    composer install
    ````
3. **Copy environment file and configure database**
    ````bash
    cp .env.example .env
    # Edit DB_CONNECTION, DB_DATABASE, etc.
    ````
4. **Generate app key**
    ````bash
    php artisan key:generate
    ````
5. **Run migrations**
    ````bash
    php artisan migrate --seed
    ````
6. **Run tests**
    ````bash
    php artisan test
    ````
    
## 🚀 Implementation Summary
1. **Ran all tests**
   * Ran all tests to find out the requirements and what to develop.
   * Also understood the flow and how to do the implementation.
2. **Created and completed all Service classes**
    * MerchantService → create/update merchant, find by email, payout unpaid orders.
    * AffiliateService → register new affiliates linked to merchants.
    * OrderService → process incoming orders, handle duplicate order IDs, register affiliates if needed.

3. **Implemented Order Webhook flow**
    * Added WebhookController with validation and JSON responses.
    * Integrated OrderService for processing validated payloads.

4. **Built PayoutOrderJob**
    * Used ApiService to send payouts for order commissions.
    * Wrapped in a DB transaction to ensure rollback on failure.

5. **Implemented ApiService::sendPayout() for API integration**

6. **Completed MerchantController::orderStats**
    * Returned {count, revenue, commissions_owed} for orders in a date range.

7. **Fixed issues**
    * Added missing columns (external_order_id) via migrations.
    * Correctly returned affiliate instances in OrderService.
    * Returned proper JSON from controllers (->json()).

## 🧪 Running Tests
1. **Run the full test suite**
    ````bash
    php artisan test
    ````
2. **Run a specific group**
    ````bash
    php artisan test --filter=OrderService
    php artisan test --filter=PayoutOrderJob
    php artisan test --filter=MerchantHttpTest
    ````
    * All tests have been passed.

## 📂 Project Structure
* app/Services – Business logic (MerchantService, AffiliateService, OrderService, ApiService)
* app/Jobs/PayoutOrderJob.php – Handles payouts with rollback on failure.
* app/Http/Controllers – WebhookController, MerchantController.
* database/migrations – Database schema.
* tests/Feature – Feature tests for services, controllers, jobs.
* tests/Unit – Example unit test.

## 💡 Notes
* The project uses SQLite (default for testing).
* Controllers return JSON responses only.
* Jobs and services are fully covered by feature tests.

## ✅ Final Status
* All feature tests pass:
   <img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/b428f28c-69ae-496f-a92b-5bb8f0fa8d86" />
   
Tests:  15 passed
Time:   <your-time>
Developed & maintained by Raja Aqeel Zafar

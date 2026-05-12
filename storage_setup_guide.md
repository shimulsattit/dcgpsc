# Cloudflare R2 Storage Setup Guide for Laravel

এই গাইডটি অনুসরণ করে আপনি সহজেই লারাভেল প্রজেক্টে Cloudflare R2 স্টোরেজ সেটআপ করতে পারবেন।

## ১. ক্লাউডফ্লেয়ার ড্যাশবোর্ড (Cloudflare Dashboard) ধাপসমূহ:

### বাকেট তৈরি (Create Bucket):
1. [Cloudflare Dashboard](https://dash.cloudflare.com/) এ গিয়ে বাম পাশের মেনু থেকে **R2** সিলেক্ট করুন।
2. **Create Bucket** বাটনে ক্লিক করুন।
3. একটি নাম দিন (যেমন: `my-app-storage`) এবং বাকেটটি তৈরি করুন।

### API টোকেন তৈরি (Create API Token):
1. R2 ওভারভিউ পেজে ডান পাশে **"Manage R2 API Tokens"** এ ক্লিক করুন।
2. **"Create API Token"** বাটনে ক্লিক করুন।
3. টোকেনের নাম দিন এবং Permissions হিসেবে **"Object Read & Write"** সিলেক্ট করুন।
4. **"Create Token"** এ ক্লিক করলে আপনি নিচের তথ্যগুলো পাবেন:
   - **Access Key ID**: এটি `.env` এর `R2_ACCESS_KEY_ID` তে বসবে।
   - **Secret Access Key**: এটি `.env` এর `R2_SECRET_ACCESS_KEY` তে বসবে।
   - **S3 Endpoint**: এটি আপনার Account ID এর মাধ্যমে তৈরি হয় (যেমন: `https://<account-id>.r2.cloudflarestorage.com`)। এটি `.env` এর `R2_ENDPOINT` তে বসবে।

### পাবলিক ইউআরএল সেটআপ (Public URL Setup):
1. আপনার বাকেটের ভেতরে গিয়ে **Settings** ট্যাবে ক্লিক করুন।
2. **Public Development URL** এ গিয়ে **Allow Access** ক্লিক করুন।
3. এখানে আপনি একটি লিঙ্ক পাবেন (যেমন: `https://pub-xxxx.r2.dev`)। এটি আপনার `.env` ফাইলের `R2_URL` হিসেবে ব্যবহার হবে।

---

## ২. লারাভেল প্রজেক্ট কনফিগারেশন:

### প্রয়োজনীয় প্যাকেজ ইনস্টল:
টার্মিনালে নিচের কমান্ডটি রান করুন:
```bash
composer require league/flysystem-aws-s3-v3
```

### .env ফাইল আপডেট:
আপনার `.env` ফাইলে নিচের কি (Keys) গুলো যোগ করুন:
```env
FILESYSTEM_DISK=r2

R2_ACCESS_KEY_ID=your_access_key_id
R2_SECRET_ACCESS_KEY=your_secret_access_key
R2_BUCKET=your_bucket_name
R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
R2_URL=https://your-public-url.r2.dev
```

### config/filesystems.php আপডেট:
`disks` এরিয়ার ভেতরে নিচের কোডটি যুক্ত করুন:
```php
'r2' => [
    'driver' => 's3',
    'key' => env('R2_ACCESS_KEY_ID'),
    'secret' => env('R2_SECRET_ACCESS_KEY'),
    'region' => 'auto',
    'bucket' => env('R2_BUCKET'),
    'url' => env('R2_URL'),
    'endpoint' => env('R2_ENDPOINT'),
    'use_path_style_endpoint' => true,
    'visibility' => 'public',
    'throw' => true,
],
```

---

## ৩. ফাইল আপলোড ও ব্যবহার:

এখন আপনি কোডে নিচের মত করে স্টোরেজ ব্যবহার করতে পারবেন:

**ফাইল আপলোড করা:**
```php
use Illuminate\Support\Facades\Storage;

// সরাসরি বাকেটে ফাইল সেভ করা
$path = $request->file('file')->store('uploads', 'r2');
```

**ফাইলের পাবলিক ইউআরএল পাওয়া:**
```php
$url = Storage::disk('r2')->url($path);
```

**ফাইল ডিলিট করা:**
```php
Storage::disk('r2')->delete($path);
```

---

### টিপস:
*   **Filament Users:** যদি Filament ব্যবহার করেন, তবে Form Schema তে `->disk('r2')` এবং `->visibility('public')` যুক্ত করুন।
*   **SSL Issue (Local):** যদি লোকাল সার্ভারে SSL Error পান, তবে `filesystems.php` এর `r2` কনফিগারেশনে `'http' => ['verify' => false]` যোগ করতে পারেন।

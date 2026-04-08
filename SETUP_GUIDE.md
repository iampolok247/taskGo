# Task Go - Complete Setup Guide

## Overview
Task Go is a task-based earning platform built with Laravel. This guide covers installation, configuration, and WebView mobile app integration.

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js >= 16 (optional, for asset compilation)
- Apache/Nginx web server

---

## Installation

### 1. Clone/Extract Project

```bash
cd /path/to/your/project
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskgo_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Set Permissions (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Start Development Server

```bash
php artisan serve
```

---

## Default Login Credentials

| Role  | Email              | Password |
|-------|-------------------|----------|
| Admin | admin@taskgo.com  | password |
| Agent | agent@taskgo.com  | password |
| User  | user@taskgo.com   | password |

---

## Production Deployment

### Apache Virtual Host

```apache
<VirtualHost *:80>
    ServerName taskgo.yourdomain.com
    DocumentRoot /var/www/taskgo/public

    <Directory /var/www/taskgo/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/taskgo_error.log
    CustomLog ${APACHE_LOG_DIR}/taskgo_access.log combined
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name taskgo.yourdomain.com;
    root /var/www/taskgo/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Production .env Settings

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://taskgo.yourdomain.com

SESSION_SECURE_COOKIE=true
```

---

## WebView Mobile App Integration

### Android WebView Setup

#### MainActivity.java

```java
package com.yourcompany.taskgo;

import android.os.Bundle;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.WebChromeClient;
import android.webkit.ValueCallback;
import android.content.Intent;
import android.net.Uri;
import android.app.Activity;
import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {
    private WebView webView;
    private ValueCallback<Uri[]> filePathCallback;
    private static final int FILE_CHOOSER_REQUEST = 1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webview);
        setupWebView();
        webView.loadUrl("https://taskgo.yourdomain.com");
    }

    private void setupWebView() {
        WebSettings settings = webView.getSettings();
        
        // Enable JavaScript
        settings.setJavaScriptEnabled(true);
        
        // Enable DOM Storage
        settings.setDomStorageEnabled(true);
        
        // Enable file access for image uploads
        settings.setAllowFileAccess(true);
        settings.setAllowContentAccess(true);
        
        // Enable zoom
        settings.setSupportZoom(true);
        settings.setBuiltInZoomControls(true);
        settings.setDisplayZoomControls(false);
        
        // Responsive viewport
        settings.setUseWideViewPort(true);
        settings.setLoadWithOverviewMode(true);
        
        // Cache settings
        settings.setCacheMode(WebSettings.LOAD_DEFAULT);
        
        // Mixed content (if using HTTP resources on HTTPS)
        settings.setMixedContentMode(WebSettings.MIXED_CONTENT_COMPATIBILITY_MODE);

        // Handle page navigation
        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                if (url.startsWith("https://taskgo.yourdomain.com") || 
                    url.startsWith("http://taskgo.yourdomain.com")) {
                    return false; // Load in WebView
                }
                // Open external links in browser
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                startActivity(intent);
                return true;
            }
        });

        // Handle file uploads
        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public boolean onShowFileChooser(WebView webView, 
                    ValueCallback<Uri[]> callback, 
                    FileChooserParams params) {
                filePathCallback = callback;
                Intent intent = params.createIntent();
                startActivityForResult(intent, FILE_CHOOSER_REQUEST);
                return true;
            }
        });
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == FILE_CHOOSER_REQUEST) {
            if (filePathCallback != null) {
                Uri[] results = null;
                if (resultCode == Activity.RESULT_OK && data != null) {
                    String dataString = data.getDataString();
                    if (dataString != null) {
                        results = new Uri[]{Uri.parse(dataString)};
                    }
                }
                filePathCallback.onReceiveValue(results);
                filePathCallback = null;
            }
        }
    }

    @Override
    public void onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack();
        } else {
            super.onBackPressed();
        }
    }
}
```

#### activity_main.xml

```xml
<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <WebView
        android:id="@+id/webview"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</RelativeLayout>
```

#### AndroidManifest.xml Permissions

```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.CAMERA" />

<application
    android:usesCleartextTraffic="true"
    android:hardwareAccelerated="true"
    ...>
```

### iOS WKWebView Setup

#### ViewController.swift

```swift
import UIKit
import WebKit

class ViewController: UIViewController, WKNavigationDelegate, WKUIDelegate {
    
    var webView: WKWebView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        let config = WKWebViewConfiguration()
        config.allowsInlineMediaPlayback = true
        
        webView = WKWebView(frame: view.bounds, configuration: config)
        webView.autoresizingMask = [.flexibleWidth, .flexibleHeight]
        webView.navigationDelegate = self
        webView.uiDelegate = self
        
        // Allow swipe back navigation
        webView.allowsBackForwardNavigationGestures = true
        
        view.addSubview(webView)
        
        if let url = URL(string: "https://taskgo.yourdomain.com") {
            webView.load(URLRequest(url: url))
        }
    }
    
    // Handle external links
    func webView(_ webView: WKWebView, decidePolicyFor navigationAction: WKNavigationAction, decisionHandler: @escaping (WKNavigationActionPolicy) -> Void) {
        if let url = navigationAction.request.url {
            if url.host == "taskgo.yourdomain.com" {
                decisionHandler(.allow)
            } else {
                UIApplication.shared.open(url)
                decisionHandler(.cancel)
            }
        } else {
            decisionHandler(.allow)
        }
    }
    
    // Handle file uploads
    func webView(_ webView: WKWebView, runOpenPanelWith parameters: WKOpenPanelParameters, initiatedByFrame frame: WKFrameInfo, completionHandler: @escaping ([URL]?) -> Void) {
        let picker = UIImagePickerController()
        picker.sourceType = .photoLibrary
        picker.delegate = self
        present(picker, animated: true)
    }
}

extension ViewController: UIImagePickerControllerDelegate, UINavigationControllerDelegate {
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [UIImagePickerController.InfoKey : Any]) {
        picker.dismiss(animated: true)
        // Handle selected image
    }
}
```

---

## Flutter WebView Integration

### pubspec.yaml

```yaml
dependencies:
  webview_flutter: ^4.4.2
  permission_handler: ^11.0.1
```

### main.dart

```dart
import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

void main() {
  runApp(const TaskGoApp());
}

class TaskGoApp extends StatelessWidget {
  const TaskGoApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Task Go',
      theme: ThemeData(primarySwatch: Colors.blue),
      home: const WebViewScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}

class WebViewScreen extends StatefulWidget {
  const WebViewScreen({super.key});

  @override
  State<WebViewScreen> createState() => _WebViewScreenState();
}

class _WebViewScreenState extends State<WebViewScreen> {
  late final WebViewController controller;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(
        NavigationDelegate(
          onProgress: (int progress) {
            if (progress == 100) {
              setState(() => isLoading = false);
            }
          },
          onPageStarted: (String url) {
            setState(() => isLoading = true);
          },
          onPageFinished: (String url) {
            setState(() => isLoading = false);
          },
        ),
      )
      ..loadRequest(Uri.parse('https://taskgo.yourdomain.com'));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Stack(
          children: [
            WebViewWidget(controller: controller),
            if (isLoading)
              const Center(child: CircularProgressIndicator()),
          ],
        ),
      ),
    );
  }
}
```

---

## API Endpoints (For Future Mobile App)

If you need a native mobile app with API integration, here are recommended endpoints:

```
POST   /api/auth/login
POST   /api/auth/register
POST   /api/auth/logout
GET    /api/user/profile
PUT    /api/user/profile
GET    /api/tasks
GET    /api/tasks/{id}
POST   /api/tasks/{id}/submit
GET    /api/wallet
GET    /api/transactions
POST   /api/deposits
POST   /api/withdrawals
GET    /api/referrals
```

---

## Troubleshooting

### Common Issues

1. **500 Error**: Check storage permissions and .env file
2. **CSRF Token Mismatch**: Clear browser cache or session
3. **File Upload Not Working**: Check php.ini upload limits
4. **Images Not Loading**: Run `php artisan storage:link`

### PHP Settings (php.ini)

```ini
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 60
memory_limit = 256M
```

---

## Support

For support and customization requests, contact the developer.

---

## License

This project is proprietary software. Unauthorized distribution is prohibited.

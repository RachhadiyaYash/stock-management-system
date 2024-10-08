﻿# Stock Management System

## Overview

This Stock Management System is a web application designed for managing inventory and sales in a retail environment. The system includes functionalities for user authentication, stock management, bill creation, and system settings. The application is built using PHP, MySQL, and Bootstrap, with a focus on simplicity and usability.

## Features

### 1. Login Page

- **Functionality**: Allows users to log in using their credentials.
- **Redirects**: Upon successful login, users are redirected to the Home page.

### 2. Home Page

- **Functionality**: Provides an overview and navigation to other sections of the application.
- **Links**: Navigate to Add Stock, View Stock, Make Bill, Settings, and Logout pages.

### 3. Add Stock Page

- **Functionality**: Allows the admin to add new stock items to the inventory.
- **Form Fields**:
  - **Brand Name**: Dropdown to select or add new brands.
  - **Product Type**: Dropdown to select or add new product types.
  - **Model Name**: Dropdown to select or add new model names.
  - **Price**: Input field for setting the price of the product.
  - **Selling Price**: Input field for setting the selling price.
  - **Quantity**: Input field for entering the quantity of the product.
  - **Product ID**: Unique identifier for the product.
  - **Specifications**: Text field for additional product details.

### 4. View Stock Page

- **Functionality**: Displays a list of all stock items.
- **Search Bar**: Filter stock based on brand name, product type, product ID, price, and quantity.
- **Actions**: Each item has a delete button to remove it from the inventory.

### 5. Make Bill Page

- **Functionality**: Allows users to generate bills for sales.
- **Form Fields**:
  - **Customer Name**: Input field for entering the customer's name.
  - **Product ID**: Input field for entering the product ID.
  - **Quantity**: Input field for entering the quantity of the product.
  - **Automatically Fetched Details**: Brand name, product type, model name, and selling price are auto-filled based on the Product ID.
  - **Total Amount**: Automatically calculated based on quantity and selling price.


### 6. Settings Page

- **Functionality**: Allows the admin to manage the dropdown options used in the Add Stock page.
- **Settings**:
  - **Brand Names**: Add or delete brand names.
  - **Product Types**: Add or delete product types.
  - **Model Names**: Add or delete model names.

### 7. Logout Page

- **Functionality**: Logs the user out and redirects them to the Login page.

## Technology Stack

- **Frontend**: HTML, CSS, Bootstrap, JavaScript (jQuery)
- **Backend**: PHP
- **Database**: MySQL

## Contributing

Feel free to open issues or submit pull requests for any improvements or bug fixes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

# CSV Import Guide

This guide explains how to import data into the finance application using CSV files.

## Available Import Actions

The application supports importing data for three models:
- **Institutions** - Banks, investment companies, credit unions, etc.
- **Accounts** - Bank accounts, credit cards, investment accounts, etc.
- **Subscriptions** - Recurring services and subscriptions

## Import Locations

Each import action is available as a button in the header of the respective resource pages:
- Institutions: `/admin/institutions` - "Import Institutions" button
- Accounts: `/admin/accounts` - "Import Accounts" button  
- Subscriptions: `/admin/subscriptions` - "Import Subscriptions" button

## CSV Format Requirements

### Institutions CSV Format

**Required columns:**
- `name` - Institution name (e.g., "Chase Bank")
- `type` - Institution type (see valid values below)

**Optional columns:**
- `website` - Institution website URL
- `description` - Institution description

**Valid type values:**
- `bank` - Bank
- `investment_company` - Investment Company
- `credit_union` - Credit Union
- `brokerage` - Brokerage
- `other` - Other

**Example:**
```csv
name,type,website,description
Chase Bank,bank,https://chase.com,One of the largest banks in the United States
Vanguard,investment_company,https://vanguard.com,Investment management company
```

### Accounts CSV Format

**Required columns:**
- `name` - Account name (e.g., "Primary Checking")
- `type` - Account type (see valid values below)
- `amount` - Current balance (numeric value)

**Optional columns:**
- `institution_name` - Name of the institution (must match existing institution)

**Valid type values:**
- `checking` - Checking
- `savings` - Savings
- `credit_card` - Credit Card
- `money_market` - Money Market
- `cd` - Certificate of Deposit
- `investment` - Investment
- `other` - Other

**Example:**
```csv
name,type,amount,institution_name
Primary Checking,checking,2500.50,Chase Bank
Emergency Savings,savings,10000.00,Chase Bank
Visa Credit Card,credit_card,-1500.25,Chase Bank
```

### Subscriptions CSV Format

**Required columns:**
- `name` - Subscription name (e.g., "Netflix")
- `price` - Monthly price (numeric value)
- `billing_frequency` - How often you're billed (see valid values below)
- `category` - Subscription category (see valid values below)
- `status` - Current status (see valid values below)

**Optional columns:**
- `next_billing_date` - Next billing date (YYYY-MM-DD format)
- `description` - Additional notes about the subscription

**Valid billing_frequency values:**
- `weekly` - Weekly
- `monthly` - Monthly
- `yearly` - Yearly

**Valid category values:**
- `entertainment` - Entertainment
- `software` - Software
- `news` - News & Media
- `fitness` - Fitness & Health
- `education` - Education
- `productivity` - Productivity
- `cloud_storage` - Cloud Storage
- `music` - Music
- `video` - Video Streaming
- `gaming` - Gaming
- `food_delivery` - Food Delivery
- `transportation` - Transportation
- `other` - Other

**Valid status values:**
- `active` - Active
- `paused` - Paused
- `cancelled` - Cancelled
- `expired` - Expired

**Example:**
```csv
name,price,billing_frequency,category,status,next_billing_date,description
Netflix,15.99,monthly,entertainment,active,2024-02-15,Streaming service
Spotify,9.99,monthly,music,active,2024-02-10,Music streaming
```

## Import Process

1. **Prepare your CSV file** - Ensure it follows the format requirements above
2. **Navigate to the resource page** - Go to the page for the data you want to import
3. **Click the import button** - Look for the "Import [Model]" button in the page header
4. **Upload your CSV file** - Select your prepared CSV file
5. **Review the results** - The system will show you how many records were imported and any errors

## Error Handling

The import process includes comprehensive error handling:

- **Validation errors** - Invalid data types, missing required fields, invalid enum values
- **Relationship errors** - For accounts, if the institution name doesn't match an existing institution
- **Row-by-row processing** - Each row is processed individually, so some rows may succeed while others fail
- **Detailed error messages** - Errors include the row number and specific issue description
- **Success notifications** - Shows how many records were successfully imported

## Sample Files

Sample CSV files are available in the `storage/app/imports/` directory:
- `sample_institutions.csv` - Example institutions data
- `sample_accounts.csv` - Example accounts data  
- `sample_subscriptions.csv` - Example subscriptions data

## Tips for Success

1. **Use exact values** - For enum fields (type, category, etc.), use the exact values listed above
2. **Check institution names** - For accounts, ensure institution names exactly match existing institutions
3. **Date format** - Use YYYY-MM-DD format for dates
4. **Numeric values** - Ensure amounts and prices are numeric (no currency symbols)
5. **Test with small files** - Start with a few records to test the format before importing large datasets

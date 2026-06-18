import pandas as pd
import json
import os

file_path = "/Users/priyanshunayak/Desktop/inven/IMS.xlsx"
output_dir = "/Users/priyanshunayak/Desktop/inven/database/seeders/data"
os.makedirs(output_dir, exist_ok=True)

# 1. Products (Product A and Product B)
products_data = [
    {
        "product_id": "A",
        "product_name": "Product A",
        "sku": "SKU-A",
        "fsn": "FSN-A",
        "asin": "ASIN-A",
        "updated_by": "System"
    },
    {
        "product_id": "B",
        "product_name": "Product B",
        "sku": "SKU-B",
        "fsn": "FSN-B",
        "asin": "ASIN-B",
        "updated_by": "System"
    }
]

with open(os.path.join(output_dir, "products.json"), "w") as f:
    json.dump(products_data, f, indent=4)

# 2. Inward Item Codes
df = pd.read_excel(file_path, sheet_name='Sheet2')
inward_records = []
for r in range(7, df.shape[0]):
    row_vals = df.iloc[r, 6:13]
    if pd.notna(row_vals.iloc[0]) and row_vals.iloc[0] != 'ID':
        inward_records.append({
            "product_id": row_vals.iloc[1],
            "uid": row_vals.iloc[2],
            "quantity": int(row_vals.iloc[3]),
            "status": row_vals.iloc[4],
            "updated_by": row_vals.iloc[5],
            "updated_on": str(row_vals.iloc[6])
        })

with open(os.path.join(output_dir, "inward_item_codes.json"), "w") as f:
    json.dump(inward_records, f, indent=4)

# 3. Purchases
# Check sheet2 left table key-value pairs:
# ProductID: 'B', VendorID: 'a1', Quantity: 10, Price: 20, Amount: 2000
purchases_data = [
    {
        "product_id": "B",
        "date": "2026-06-18",
        "vendor_id": "a1",
        "quantity": 10,
        "price": 20.00,
        "amount": 2000.00,
        "updated_by": "Sitaram"
    }
]

with open(os.path.join(output_dir, "purchases.json"), "w") as f:
    json.dump(purchases_data, f, indent=4)

# 4. Dispatch Item Codes
# Columns 15-19: ID, ProductID, UID, Quantity, Status
dispatch_records = []
for r in range(7, df.shape[0]):
    row_vals = df.iloc[r, 15:20]
    if pd.notna(row_vals.iloc[0]) and row_vals.iloc[0] != 'ID' and row_vals.iloc[0] != 'DispatchItemCode Master':
        dispatch_records.append({
            "product_id": row_vals.iloc[1],
            "uid": row_vals.iloc[2],
            "quantity": int(row_vals.iloc[3]),
            "status": row_vals.iloc[4],
            "updated_by": "Sitaram"
        })

with open(os.path.join(output_dir, "dispatch_item_codes.json"), "w") as f:
    json.dump(dispatch_records, f, indent=4)

# 5. Sales
# Let's seed a sample sale corresponding to the dispatched unit (Zig0008 of Product A, sold)
sales_data = [
    {
        "portal_id": "Portal-1",
        "product_id": "A",
        "order_date": "2026-06-25",
        "quantity": 1,
        "updated_by": "Sitaram"
    }
]

with open(os.path.join(output_dir, "sales.json"), "w") as f:
    json.dump(sales_data, f, indent=4)

# 6. Users
# Let's seed two initial users to match User Master column schema
users_data = [
    {
        "name": "Sitaram",
        "email": "sitaram@example.com",
        "password": "password",
        "status": "Active"
    },
    {
        "name": "Test User",
        "email": "test@example.com",
        "password": "password",
        "status": "Active"
    }
]

with open(os.path.join(output_dir, "users.json"), "w") as f:
    json.dump(users_data, f, indent=4)

print("All JSON seed files generated successfully in database/seeders/data/")

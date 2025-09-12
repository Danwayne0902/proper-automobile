# Vehicle Image Upload Guide

## How to Upload Vehicle Images

### For Dealers and Administrators

1. **Navigate to Automobile Management**
   - Go to Dashboard → Automobiles
   - Click "Add Automobile" or edit existing vehicles

2. **Image Upload Process**
   - During automobile creation/editing, scroll to the "Images" section
   - Click "Choose Files" to select up to 10 images
   - Supported formats: JPG, PNG, GIF (max 2MB each)
   - Images will be automatically resized and optimized

3. **Image Management**
   - Preview images before saving
   - Remove unwanted images using the × button
   - Reorder images by drag-and-drop (if implemented)
   - First image becomes the primary display image

4. **Best Practices**
   - Use high-quality, well-lit photos
   - Include multiple angles: front, back, sides, interior
   - Show important features and details
   - Ensure images are clear and not blurry

### Storage Information

- Images are stored in: `storage/app/public/automobiles/`
- Accessible via: `/storage/automobiles/filename.jpg`
- Demo images are in: `storage/app/public/demo/`
- Maximum 10 images per vehicle
- Automatic file validation and security checks

### Demo Images Available

Current demo images include:
- BMW X5 (Blue luxury SUV)
- Mercedes C-Class (Silver sedan)
- Audi A4 (Red sedan)

### Troubleshooting

- **Image not uploading**: Check file size (max 2MB) and format (JPG/PNG/GIF)
- **Permission errors**: Ensure storage directory has write permissions
- **Images not displaying**: Check if storage link exists (`php artisan storage:link`)

For technical support, contact system administrator.

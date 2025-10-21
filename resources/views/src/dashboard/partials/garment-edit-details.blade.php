    <a href="{{ route('dashboard.garment.index') }}" class="back-btn">←</a>
    <h2>Edit Garment</h2>
    <form class="garment-form" enctype="multipart/form-data">
        <input hidden name="product_id" value="">
        <div class="form-row">
            <div>
                <label>Upload Image</label>
                <input name="poster" type="file">
            </div>
            <div>
                <label for="condition">Condition</label>
                <select id="condition" name="condition_id" class="green-input">
                    <option value="">Select Condition</option>
                    <option value="ok">Good</option>
                    <option value="damaged">Damaged</option>
                    <option value="needs-repair">Needs Repair</option>
                </select>
            </div>
        </div>
        <div class="tight-group">
            <label>Product Name</label>
            <input type="text" name="product_name" value="">
            <label>Description</label>
            <textarea name="additional_description" rows="4"></textarea>
        </div>
        <div class="form-row">
            <div>
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="">Select Type</option>
                    <option value="gown">Gown</option>
                    <option value="tuxedo">Tuxedo</option>
                    <option value="barong">Barong</option>
                    <option value="filipiniana">Filipiniana</option>
                </select>
            </div>
            <div>
                <label for="sub-type">Sub-type</label>
                <select id="sub-type" name="sub_type">
                    <option value="">Select Sub-type</option>
                    <option value="evening-gown">Evening Gown</option>
                    <option value="wedding-gown">Wedding Gown</option>
                    <option value="casual-gown">Casual Gown</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label>Rental Price</label>
                <input type="number" name="rent_price">
            </div>
            <div>
                <label for="size">Size</label>
                <select id="size" name="measurement">
                    <option value="">Select Size</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label>Width</label>
                <input type="number" name="width">
            </div>
            <div>
                <label>Height</label>
                <input type="number" name="length">
            </div>
        </div>
        <button type="submit" class="green-button">Save Changes</button>
    </form>

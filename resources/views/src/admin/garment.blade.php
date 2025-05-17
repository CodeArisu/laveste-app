<x-layouts.admin>
  @push('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">
  @endpush

   @if(Session('success'))
    <x-fragments.alert-response message="{{ Session('success') }}" type="success"/>
  @endif

  <div class="product-page">
    <div class="header-section">
      <h2 class="section-title">Garments</h2>
{{-- 
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">Garments</h2>          
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class='text-center' >Name</th>
                    <th class='text-center' >Price</th>
                    <th class='text-center' >Type</th>
                    <th class='text-center' >Sub-type(s)</th>
                    <th class='text-center' >Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody> <!-- Only one tbody here -->
              @foreach ($garments as $garment)
                <tr>
                    <td>{{ $garment->id }}</td>
                    <td class='text-center'></td>
                    <td class='text-center'>{{ $garment->rent_price }}</td>
                    <td class='text-center'>{{ $garment->product->types->type_name }}</td>
                    <td class='text-center'>
                      @foreach($garment->product->subtypes as $subtypes)
                        {{ $subtypes->subtype_name }}
                      @endforeach
                    </td>
                    <td class='text-center'>
                      @if($garment->condition->condition_name == 'ok')
                        <span class="status good">{{ $garment->condition->condition_name }}</span>
                      @else
                        <span class="status damaged">{{ $garment->condition->condition_name }}</span>
                      @endif
                    </td>
                    <td class='text-end'>
                      <i class="fa-solid fa-ellipsis-vertical"></i>
                    </td>
                </tr>
                @endforeach
            </tbody> <!-- Only one tbody here -->
        </table>

        <div class="sidepanel">
            <a href="{{ url()->previous() }}" class="back-btn">←</a>
            <br><br>
            <h3 class="sidepanel-title">Product Information</h3>
            <div class="sidepanel-body">
              <div class="sidepanel-content">
                <img src="/assets/images/h1.png" alt="Gown Image" class="product-img">
                <div class="product-info">
                  <h2 class="product-name">Very pretty cute gown</h2>
                  <br><br><br>
                  <div class="info-row">
                    <span class="label">Type</span>
                    <span class="value">Gown</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Sub-type</span>
                    <span class="value">Evening Gown</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Size</span>
                    <span class="value">Medium</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Rental Price</span>
                    <span class="value">3,500.00</span>
                  </div>
                </div>
              </div>
          
              <div class="description-section">
                <h4>Description</h4>
                <br>
                <p>
                    This elegant gown features a chic one-shoulder design with cascading ruffles that add a dramatic flair and graceful movement. 
                    The bodice is form-fitting, accentuating the waist before flowing into a full-length skirt that sways beautifully with each step. 
                    Crafted from premium satin fabric, it offers a luxurious feel and subtle sheen under the light. 
                    Perfect for formal occasions, evening galas, or red-carpet events, this gown combines timeless sophistication with a touch of modern glamour — 
                    ensuring you make a lasting impression wherever you go.
                  </p>
                  
              </div>

              <br>
          
              <div class="status-section">
                <h4>Status</h4>
                <div class="status-pill">Good</div>
              </div>
          
              <div class="action-footer">
                <button class="add-button">Add to Display</button>
              </div>
            </div>
        
          </div> --}}
    </div>

    <table class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Type</th>
          <th>Sub-type</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <!-- Only one tbody here -->
        @foreach ($garments as $garment)
        <tr class="product-row">
          <td>{{ $garment->id }}</td>
          <td>{{ $garment->product->product_name }}</td>
          <td>{{ $garment->rent_price }}</td>
          <td>{{ $garment->product->types->type_name }}</td>
          <td>
            {{-- loop through array of subtypes --}}
            @foreach($garment->product->subtypes as $subtypes)
            {{ $subtypes->subtype_name }}
            @endforeach
          </td>
          <td>
            {{-- for condition status design conditions --}}
            @if($garment->condition->condition_name == 'ok')
            <span class="status good">{{ $garment->condition->condition_name }}</span>
            @else
            <span class="status damaged">{{ $garment->condition->condition_name }}</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody> <!-- Only one tbody here -->
    </table>

    <div class="sidepanel">
      <a href="{{ url()->previous() }}" class="back-btn">←</a>
      <br><br>
      <h3 class="sidepanel-title">Product Information</h3>
      <div class="sidepanel-body">
        <div class="sidepanel-content">
          <img src="/assets/images/h1.png" alt="Gown Image" class="product-img">
          <div class="product-info">
            <h2 class="product-name">{{ $garment->product->product_name }}</h2>
            <br><br><br>
            <div class="info-row">
              <span class="label">Type</span>
              <span class="value">Gown</span>
            </div>
            <div class="info-row">
              <span class="label">Sub-type</span>
              <span class="value">Evening Gown</span>
            </div>
            <div class="info-row">
              <span class="label">Size</span>
              <span class="value">Medium</span>
            </div>
            <div class="info-row">
              <span class="label">Rental Price</span>
              <span class="value">3,500.00</span>
            </div>
          </div>
        </div>

        <div class="description-section">
          <h4>Description</h4>
          <br>
          <p>
            This elegant gown features a chic one-shoulder design with cascading ruffles that add a dramatic flair and
            graceful movement.
            The bodice is form-fitting, accentuating the waist before flowing into a full-length skirt that sways
            beautifully with each step.
            Crafted from premium satin fabric, it offers a luxurious feel and subtle sheen under the light.
            Perfect for formal occasions, evening galas, or red-carpet events, this gown combines timeless
            sophistication with a touch of modern glamour —
            ensuring you make a lasting impression wherever you go.
          </p>

        </div>

        <br>

        <div class="status-section">
          <h4>Status</h4>
          <div class="status-pill">Good</div>
        </div>

        <div class="action-footer">
          <button class="edit-button">Edit</button>
          <button class="add-button">Add to Display</button>
        </div>
      </div>

    </div>




    <div id="editGarmentPanel" class="side-panel">
      <br>
     <a href="{{ url()->previous() }}" class="back-btn">←</a>
      <br><br>
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
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    // Show product info sidepanel when a product row is clicked
    const rows = document.querySelectorAll('.product-row');
    const sidePanel = document.querySelector('.sidepanel');

    rows.forEach(row => {
      row.addEventListener('click', () => {
        sidePanel.classList.add('visible');
      });
    });

    // Show edit garment panel when edit button is clicked
    const editButton = document.querySelector('.edit-button');
    const editGarmentPanel = document.getElementById('editGarmentPanel');

    if (editButton) {
      editButton.addEventListener('click', () => {
        editGarmentPanel.style.display = 'block';
      });
    }

    // Hide edit garment panel when back button is clicked
    const backBtn2 = document.querySelector('.back-btn2');

    if (backBtn2) {
      backBtn2.addEventListener('click', (e) => {
        e.preventDefault();
        editGarmentPanel.style.display = 'none';
      });
    }
  });
</script>

</x-layouts.admin>
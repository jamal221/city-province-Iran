<div id="mdoalShowCites" class="w3-modal w3-animate-zoom" style="z-index:10000; width: 100%">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container ">
            <div class="d-flex justify-content-around mt-2">
                <p style="margin: 10px 0px; text-align: center; font-size: 20px; font-weight: bold;">انتخاب شهر</p>
                <button class="deletAll-btn" onclick="removeAllFoTheSelection()">حذف همه</button>
            </div>
            <div id="showSelectedList" style="display: flex; flex-wrap: wrap; gap: 5px;"></div>
            <div class="d-flex justify-content-center">
                <input type="text" placeholder="جستجو شهر" class="search-city-input" onkeyup="searchCity(this.value)">
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn-danger" id="selAllCountryBtn">انتخاب همه ی ایران</button>
            </div>
            <span onclick="CloseModal('mdoalShowCites')" class="w3-button w3-display-topright"
                style="color: red; font-size:25px;">&times;</span>
        </header>
        <div class="w3-container" id="mainResultSession" style="max-height: 500px; overflow-y: auto;">
            <div class="container my-5">
                <table class="table table-bordered">
                    <tbody>
                       
                        @foreach($fetchProvince as $province)
                        <tr class="province-row" id="province-tr-{{$province['id']}}">
                            <td>
                                <span class="province-name" style="cursor: pointer;"
                                    data-province-id="{{ $province['id'] }}"
                                    data-original-text="{{ $province['name'] }}"
                                    onclick="toggleCityList({{ $province['id'] }})">
                                    {{ $province['name'] }}
                                    <i class="bi bi-chevron-down arrow-icon"></i>
                                </span>
                                <div class="city-list" id="cities-{{ $province['id'] }}" style="display: none;">
                                    <ul class="list-unstyled">
                                        <li>
                                            <input type="checkbox" name="provinces[]" class="city-item-for-search"
                                                value="{{ $province['id'] }}" data-city-name="{{ $province['name'] }}"
                                                onchange="handleProvinceSelection({{ $province['id'] }}, this)"
                                                onclick="toggleAllCheckboxes(this, {{ $province['id'] }})">
                                            همه شهر های {{ $province['name'] }}
                                        </li>
                                        @foreach($province['cities'] as $city)
                                        <li>
                                            <input type="checkbox" name="cities[]" class="city-item-for-search"
                                                value="{{ $city['id'] }}" data-city-name="{{ $city['name'] }}"
                                                data-province-id="{{ $province['id'] }}"
                                                onchange="handleCitySelection({{ $province['id'] }}, {{ $city['id'] }}, this)">
                                            {{ $city['name'] }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="display: flex; ">
            <button type="submit" class="btn btn-ls CancelCitesToFIlter"
                style="margin: 30px; width: 40%; border: 2px solid grey;"
                onclick="CloseModal('mdoalShowCites')">انصراف</button>
            <button type="submit" class="btn btn-danger btn-ls AddCitesToFIlterAndClose"
                style="margin: 30px; width: 40%;">تأیید</button>
        </div>
    </div>
</div>


<script>
    // نمایش تعداد شهر های انتخابی بر ریو هدر

let selectedProvinces = JSON.parse(localStorage.getItem('selectedProvinces')) || {};
let selectedCities = JSON.parse(localStorage.getItem('selectedCities')) || {};

// بررسی و بازیابی مقدار از Local Storage
let selectAllCountry = localStorage.getItem('selectAllCountry');

// اگر مقدار در Local Storage موجود نبود، مقداردهی پیش‌فرض
if (selectAllCountry === null) {
    selectAllCountry = 0; // مقدار پیش‌فرض دلخواه
} else {
    selectAllCountry = parseInt(selectAllCountry); // تبدیل رشته به عدد در صورت لزوم
}

// بروزرسانی نمایش انتخاب‌ها بر اساس مقدار بازیابی‌شده
updateSelectedCityCount();

// گوش دادن به رویداد کلیک بر روی دکمه "انتخاب همه ی ایران"
$(document).on('click', '#selAllCountryBtn', function () {
    removeAllFoTheSelection();
    // مقداردهی مجدد به selectAllCountry
    selectAllCountry = -1;
    // ذخیره‌سازی در Local Storage
    localStorage.setItem('selectAllCountry', selectAllCountry);

    showSelected();
    updateSelectedCityCount();
    // closeModal('mdoalShowCites');
});


// بستن مودال
const closeModal = (modalId) => {
    document.getElementById(modalId).style.display = 'none';
};




// جستجوی چک‌باکس‌ها از طریق فیلد ورودی
// جستجوی چک‌باکس‌ها از طریق فیلد ورودی
const searchCity = (searchValue) => {
    const searchLower = searchValue.trim();
    const cityItems = document.querySelectorAll('.city-item-for-search');
    const provinceRows = document.querySelectorAll('.province-row');

    provinceRows.forEach(row => {
        const cityList = row.querySelector('.city-list');
        let hasMatch = false;

        cityList.querySelectorAll('li').forEach(item => {
            const cityName = item.querySelector('input').getAttribute('data-city-name');
            if (cityName.includes(searchLower)) {
                item.style.display = 'block';
                hasMatch = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (hasMatch) {
            row.style.display = 'table-row';
            cityList.style.display = 'block';
        } else {
            row.style.display = 'none';
        }
    });
};




// نمایش لیست شهرهای داخل یک استان
function toggleCityList(provinceId, element) {
    const cityList = document.getElementById(`cities-${provinceId}`);
    const provinceRows = document.querySelectorAll('.province-row');
    
    if (cityList.style.display === 'none' || cityList.style.display === '') {
        // Hide all province rows except the clicked one
        provinceRows.forEach(row => {
            const span = row.querySelector('.province-name');
            const list = row.querySelector('.city-list');
            
            if (span.getAttribute('data-province-id') == provinceId) {
                list.style.display = 'block';
                span.innerHTML = 'بازگشت <i class="bi bi-chevron-up"></i>';
            } else {
                row.style.display = 'none';
            }
        });
    } else {
        // Show all province rows and hide the city list
        provinceRows.forEach(row => {
            const span = row.querySelector('.province-name');
            const list = row.querySelector('.city-list');
            
            list.style.display = 'none';
            row.style.display = 'table-row';
            span.innerHTML = `${span.getAttribute('data-original-text')} <i class="bi bi-chevron-down"></i>`;
        });
    }
}

// فعال‌سازی یا غیرفعال‌سازی تمام چک باکس‌ها برای یک استان خاص
const toggleAllCheckboxes = (source, provinceId) => {
    const checkboxes = document.querySelectorAll(`#cities-${provinceId} input[type="checkbox"]`);
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
};



const handleProvinceSelection = (provinceId, checkbox) => {
    localStorage.clear("selectAllCountry");
    const cityCheckboxes = document.querySelectorAll(`#cities-${provinceId} input[type="checkbox"]`);
    if (checkbox.checked) {
        selectedProvinces[provinceId] = true;
        cityCheckboxes.forEach(cityCheckbox => {
            if(cityCheckbox.name!='provinces[]'){
                cityCheckbox.checked = true;
                selectedCities[cityCheckbox.value] = true; // ذخیره کد همه‌ی شهرهای استان
            }
        });
    } else {
        delete selectedProvinces[provinceId];
        cityCheckboxes.forEach(cityCheckbox => {
            cityCheckbox.checked = false;
            delete selectedCities[cityCheckbox.value]; // حذف کد همه‌ی شهرهای استان
        });
    }
    showSelected();
    saveSelection();
};

const handleCitySelection = (provinceId, cityId, checkbox) => {
    localStorage.clear("selectAllCountry");
    if (checkbox.checked) {
        selectedCities[cityId] = true;
    } else {
        delete selectedCities[cityId];
    }

    const cityCheckboxes = document.querySelectorAll(`#cities-${provinceId} input[name='cities[]']`);
    const allChecked = Array.from(cityCheckboxes).every(cityCheckbox => cityCheckbox.checked);

    if (allChecked) {
        selectedProvinces[provinceId] = true;
    } else {
        delete selectedProvinces[provinceId];
    }

    showSelected();
    saveSelection();
};

const showSelected = () => {
    const selectedList = document.getElementById("showSelectedList");
    selectedList.innerHTML = ''; // پاک کردن لیست قبلی
    selectAllCountry=localStorage.getItem('selectAllCountry');
    if(selectAllCountry==-1){
        const button = document.createElement('button');
        button.className = "selected-city-btn";
        button.dataset.id = -1;
        button.dataset.type = "ALL";
        button.onclick = () => removeSelection(button);
        button.innerHTML = `همه ی ایران <i class="bi bi-x-lg ms-2"></i>`;
        selectedList.appendChild(button);
    }
    // ایجاد دکمه‌ها از انتخاب‌های استان
    Object.keys(selectedProvinces).forEach(provinceId => {
        const provinceNameElement = document.querySelector(`.province-name[data-province-id="${provinceId}"]`);
        if (provinceNameElement) {
            const provinceName = " همه شهر های " + provinceNameElement.dataset.originalText;
            const button = document.createElement('button');
            button.className = "selected-city-btn";
            button.dataset.id = provinceId;
            button.dataset.type = "province";
            button.onclick = () => removeSelection(button);
            button.innerHTML = `${provinceName} <i class="bi bi-x-lg ms-2"></i>`;
            selectedList.appendChild(button);
        }
    });

    // ایجاد دکمه‌ها از انتخاب‌های شهر (فقط وقتی استان مربوطه انتخاب نشده باشد)
    Object.keys(selectedCities).forEach(cityId => {
        const cityCheckbox = document.querySelector(`input[name='cities[]'][value='${cityId}']`);
        if (cityCheckbox) {
            const cityName = cityCheckbox.closest('li').innerText.trim();
            const provinceId = cityCheckbox.dataset.provinceId;

            // نمایش دکمه‌های شهر تنها در صورتی که استان مربوطه انتخاب نشده باشد
            if (!selectedProvinces[provinceId]) {
                const button = document.createElement('button');
                button.className = "selected-city-btn";
                button.dataset.id = cityId;
                button.dataset.type = "city";
                button.onclick = () => removeSelection(button);
                button.innerHTML = `${cityName} <i class="bi bi-x-lg ms-2"></i>`;
                selectedList.appendChild(button);
            }
        }
    });
};

const saveSelection = () => {
    localStorage.setItem('selectedProvinces', JSON.stringify(selectedProvinces));
    localStorage.setItem('selectedCities', JSON.stringify(selectedCities));
    localStorage.setItem('selectAllCountry',selectAllCountry );
};




// حذف یک انتخاب
const removeSelection = (el) => {
    const id = el.dataset.id;
    const type = el.dataset.type;

    if (type === 'province') {
        const provinceCheckbox = document.querySelector(`input[name='provinces[]'][value='${id}']`);
        if (provinceCheckbox) {
            provinceCheckbox.checked = false;
            handleProvinceSelection(id, provinceCheckbox);
        }
    } else if (type === 'city') {
        const cityCheckbox = document.querySelector(`input[name='cities[]'][value='${id}']`);
        if (cityCheckbox) {
            cityCheckbox.checked = false;
            handleCitySelection(cityCheckbox.dataset.provinceId, id, cityCheckbox);
        }
    }
    else if (type === 'ALL') {
        localStorage.setItem("selectAllCountry",0);
        showSelected();
        updateSelectedCityCount();
    }
};

// حذف تمام انتخاب‌ها
const removeAllFoTheSelection = () => {
    localStorage.clear();
    Object.keys(selectedProvinces).forEach(key => delete selectedProvinces[key]);
    Object.keys(selectedCities).forEach(key => delete selectedCities[key]);
    showSelected();
    const myDiv = document.getElementById("showSelectedList");
    while (myDiv.firstChild) {
        myDiv.firstChild.remove();
    }
    const allCheckboxes = document.querySelectorAll('#mdoalShowCites input[type="checkbox"]');
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    // closeModal('mdoalShowCites');
    updateSelectedCityCount();
};

// نمایش انتخاب‌ها در شروع
showSelected();

// نمایش پیام alert با شهرهای انتخاب‌شده
const displaySelectedCities = () => {
    let selectedCityIds = Object.keys(selectedCities);
    updateSelectedCityCount();
    closeModal("mdoalShowCites");
    // if (selectedCityIds.length > 0) {
    //     alert("کد شهرهای انتخاب‌شده: " + JSON.stringify(selectedCityIds));
    // } else {
    //     alert("هیچ شهری انتخاب نشده است.");
    // }
};

// مدیریت کلیک بر روی دکمه‌های اضافه کردن شهر و بستن مودال
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('AddCitesToFIlterAndClose')) {
        displaySelectedCities();
        updateSelectedCityCount();
       
        // closeModal("mdoalShowCites");
    }
});

// اتصال تابع displaySelectedCities به دکمه تأیید
document.querySelector('.AddCitesToFIlterAndClose').addEventListener('click', displaySelectedCities);

// تابع برای نمایش تعداد شهرهای انتخابی
function updateSelectedCityCount() {
    let selectedCityIds = Object.keys(selectedCities);
    const cityCount = selectedCityIds.length;
    const cityCountElement = document.getElementById('firstSelectedCity');
    selectAllCountry=localStorage.getItem('selectAllCountry');
    if (selectAllCountry == -1) {
        cityCountElement.textContent = 'همه ی ایران';
    } else if (cityCount > 0) {
        cityCountElement.textContent = `${cityCount} شهر`;
    } else {
        cityCountElement.textContent = "";
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    showSelected();
    updateSelectedCityCount();
});


// اطمینان حاصل کنید که این تابع را پس از هر تغییر در انتخاب شهرها فراخوانی کنید
// document.querySelectorAll('input[name="cities[]"]').forEach(checkbox => {
//     checkbox.addEventListener('change', updateSelectedCityCount);
// });


</script>
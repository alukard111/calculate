// внизу есть дашборд! 
class Main {
    constructor() {
        this.submitProduct = document.querySelector('.submit__product');

        this.postName = document.querySelector('[name="postName"]')
        this.postType = document.querySelector('[name="postType"]');
        this.postWeight = document.querySelector('[name="postWeight"]');
        this.postPriceOfPacking = document.querySelector('[name="postPriceOfPacking"]');
        this.postBoughtPacks = document.querySelector('[name="postBoughtPacks"]');
        this.postMargetName =  document.querySelector('[name="postMargetName"]');
        this.postTown = document.querySelector(`[name="postTown"]`);


        this.productNameInput = {
            postNameEl: null,
            postTypeEl: null,
            postWeightEl: null,
            postPriceOfPackingEl: null,
            postBoughtPacksEl: null,
            postMargetNameEl: null,
            postTownEl: null
        };
        this.postName.addEventListener('input', (e) => {
            this.productNameInput.postNameEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        })

        this.postType.addEventListener('input', (e) => {
            this.productNameInput.postTypeEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.postWeight.addEventListener('input', (e) => {
            this.productNameInput.postWeightEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.postPriceOfPacking.addEventListener('input', (e) => {
            this.productNameInput.postPriceOfPackingEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.postBoughtPacks.addEventListener('input', (e) => {
            this.productNameInput.postBoughtPacksEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.postMargetName.addEventListener('input', (e) => {
            this.productNameInput.postMargetNameEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.postTown.addEventListener('input', (e) => {
            this.productNameInput.postTownEl = (e.currentTarget.value)
            if (e.currentTarget.classList.contains('error')) {
                e.currentTarget.classList.remove('error')
            }
        } )


        this.submitProduct.addEventListener('click', (e) => {
            let hasEmptyField = false;
            for (let product in this.productNameInput) {
                if ((typeof this.productNameInput[product] === 'string' ||
                        typeof this.productNameInput[product] === 'number') &&
                    this.productNameInput[product].length > 0)
                {
                    continue;
                } else {
                    hasEmptyField = true;
                    break;
                }
            }

            if (hasEmptyField) {
                for (let product in this.productNameInput) {
                    if (!((typeof this.productNameInput[product] === 'string' ||
                            typeof this.productNameInput[product] === 'number') &&
                        this.productNameInput[product].length > 0)) {
                        this.errorStyle(product.slice(0, -2))
                    }
                }

                e.preventDefault();
                alert('Поля должны быть заполнены');
            }
        });


    }


    errorStyle(target) {
        const input = document.querySelector(`[name="${target}"]`)

        if (input.classList.contains('error')) {
            return;
        }
        // console.log(input)
        input.classList.add('error')
    }
}


const main = new Main();



/// DASHBOARD
// даш борд создается сразу и вешает функцию сортировки на каждый столбик в таблице
// при вызове функции sortElements отправляет запрос в php sort.php
class Dashboard {
    constructor() {
        this.dashboardAllTh = document.querySelectorAll('th');
        this.dashboardAllTh.forEach((th) => {
            th.addEventListener('click', () => this.sortElements(th))
        })
    }

    sortElements(th) {
        const field = th.innerText.split(' ').join('+');
        fetch(`http://localhost:3000/model/database/sort.php?field=${field}&order=desc`, {
            method: 'GET',
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('ERORRRR')
                }

                // Обработка полученного ответа от сервера
            })
            .then(data => {
                console.log(typeof(data), 'data')
            })
            .catch(error => {
                console.log(error, 'omg');
                // Обработка ошибок при выполнении запроса
            });
    }
}

const board = new Dashboard();

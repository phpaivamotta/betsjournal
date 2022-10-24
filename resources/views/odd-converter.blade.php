<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Odd Converter') }}
        </h2>
    </x-slot>

    <x-auth-card>

        <p class="text-xl font-semibold">Odd Converter</p>

        <p class="font-medium text-sm text-gray-700">
            Convert odds type/format from either american to decimal or from decimal to american.
        </p>

        <!-- Odd Type -->
        <div class="flex items-center mt-6">

            <input class="mr-1" type="radio" name="to_odd_type" id="to_american" value="to_american">
            <x-input-label for="to_american" :value="__('To American')" />

            <input class="ml-4 mr-1" type="radio" name="to_odd_type" id="to_decimal" value="to_decimal">
            <x-input-label for="to_decimal" :value="__('To Decimal')" />

        </div>

        <!-- odd -->
        <div class="mt-4">
            <x-input-label for="odd" :value="__('Odd')" />

            <x-text-input id="odd" class="block mt-1 w-full" type="number" step="0.001" name="odd" />
        </div>

        <x-primary-button type="button" class="mt-4" id="btn">Convert</x-primary-button>

        <span class="bg-gray-400 block font-semibold mt-4 p-2 rounded-sm text-center text-white" id="converted_odd"
            style="display: none"></span>

        <span class="bg-gray-400 block font-semibold mt-4 p-2 rounded-sm text-center text-white"
            id="implied_probability" style="display: none"></span>

    </x-auth-card>


    <script>
        const button = document.getElementById('btn')
        const convertedOdd = document.getElementById('converted_odd')
        const impliedProbability = document.getElementById('implied_probability')

        button.addEventListener('click', () => {
            const odd = document.getElementById('odd').value
            const toOddType = document.getElementsByName('to_odd_type')

            if (toOddType[0].checked) {

                if (odd < 1) {
                    convertedOdd.innerHTML = 'N/A'
                    impliedProbability.innerHTML = 'N/A'
                } else if (odd == 1) {
                    convertedOdd.innerHTML = 'N/A'
                    impliedProbability.innerHTML = '100%'
                } else {
                    if (odd >= 2) {
                        convertedOdd.innerHTML = 'American: +' + decimalToAmerican(odd).toFixed(3)
                    } else {
                        convertedOdd.innerHTML = 'American: ' + decimalToAmerican(odd).toFixed(3)
                    }

                    impliedProbability.innerHTML = 'Implied Probability: ' + toImpliedProbability(odd).toFixed(2) +
                        '%'
                }

            } else if (toOddType[1].checked) {

                const decimalOdd = americanToDecimal(odd).toFixed(3)
                convertedOdd.innerHTML = 'Decimal: ' + decimalOdd
                impliedProbability.innerHTML = 'Implied Probability: ' + toImpliedProbability(decimalOdd).toFixed(
                    2) + '%'

            }

            convertedOdd.style.display = "block"
            impliedProbability.style.display = "block"
        })

        function americanToDecimal(odd) {
            if (odd > 0) {
                return (odd / 100) + 1
            } else {
                return (100 / Math.abs(odd)) + 1
            }
        }

        function decimalToAmerican(odd) {
            if (odd >= 2) {
                return (odd - 1) * 100
            } else {
                return -100 / (odd - 1)
            }
        }

        function toImpliedProbability(odd) {
            return (1 / odd) * 100
        }
    </script>
</x-app-layout>

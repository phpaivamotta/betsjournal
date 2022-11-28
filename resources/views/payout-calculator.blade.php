<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Payout Calculator') }}
        </h2>
    </x-slot>

    <x-auth-card class="sm:max-w-lg">

        <p class="text-xl font-semibold">Payout Calculator</p>

        <p class="font-medium text-sm text-gray-700">
            Calculate the payout from a bet.
        </p>

        <p class="font-medium text-sm mt-5 mb-1">
            Odd Format:
        </p>

        <!-- Odd Type -->
        <div class="flex items-center">

            <input class="mr-1" type="radio" name="odd_type" id="american" value="american" required>
            <x-input-label for="american" :value="__('American')" />

            <input class="ml-4 mr-1" type="radio" name="odd_type" id="decimal" value="decimal">
            <x-input-label for="decimal" :value="__('Decimal')" />

        </div>

        {{-- checkbox error --}}
        <span class="block mt-1 text-red-500 text-xs" id="odd_type_error" style="display: none;"></span>


        <div class="space-y-2 mt-6">

            {{-- odd --}}
            <x-input-label for="odd" :value="__('Odd')" />
            <x-text-input id="odd" class="block mt-1 w-full" type="number" step="0.001" name="odd" />

            {{-- odd error --}}
            <span class="block mt-1 text-red-500 text-xs" id="odd_error" style="display: none"></span>


            {{-- stake --}}
            <x-input-label for="stake" :value="__('Stake')" />
            <x-text-input id="stake" class="block mt-1 w-full" type="number" step="0.01" name="stake" />

            {{-- stake error --}}
            <span class="block mt-1 text-red-500 text-xs" id="stake_error" style="display: none;"></span>

        </div>

        <x-primary-button type="button" class="mt-4" id="btn">Convert</x-primary-button>

        <span class="bg-gray-400 block font-semibold mt-4 p-2 rounded-sm text-center text-white" id="payout"
            style="display: none"></span>

        <span class="bg-gray-400 block font-semibold mt-4 p-2 rounded-sm text-center text-white" id="profit"
            style="display: none"></span>

        <p class="border-gray-200 border-t-2 mt-6 pt-4 text-gray-700 text-sm">
            Being able to calculate the returns for any single bet is an essential part of betting.
            This might be one of the simplest calculations done in betting, nut it is nonetheless essential. Our <span
                class="italic">Payout Calculator</span> makes this calculation even simpler!
        </p>

        <p class="text-gray-700 text-sm mt-3">
            Every bet can essentially be broken down into three elements: the <strong>stake</strong>, the
            <strong>payout</strong>, and the <strong>profit</strong>.
            The stake is the amount being wagered on the bet, this is the amount you risk losing whenever you place a
            bet. On the other hand, the payout is the amount you can win. This consistis of the stake plus the
            <strong>profit</strong>, which is how much money the bookies are giving you for winning the bet.
        </p>

        <p class="text-gray-700 text-sm mt-3">
            For instance, lets suppose Barcelona and Real Madrid are competing in the Champions League Final. A bookie
            has the odds of Real Madrid winning the match at 2.3, in decimal format. This means that, if you stake a 100
            dollars on Real Madrid to win the match, then your potential payout is of 230 dollars and your potential
            profit is of 130 dollars.
        </p>

        <p class="text-gray-700 text-sm mt-5">
            The following formulas can be used to calculate the payout.
        </p>

        <p class="text-gray-700 text-sm mt-3 font-semibold">
            Formula for decimal odds:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            Payout &nbsp; = &nbsp; Stake &nbsp; x &nbsp; Odd
        </p>

        <p class="text-gray-700 text-sm mt-3 font-semibold">
            Formula for american odds:
        </p>

        <p class="text-gray-700 text-sm mt-1">
            Favorite (-):
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            Payout &nbsp; = &nbsp; ( Stake / (Odd / -100) ) &nbsp; + &nbsp; Stake
        </p>

        <p class="text-gray-700 text-sm mt-3">
            Underdog (+):
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            Payout &nbsp; = &nbsp; Stake &nbsp; x &nbsp; (Odd / 100) &nbsp; + &nbsp; Stake
        </p>

        <p class="text-gray-700 text-sm mt-5">
            Once the payout is known, the profit can be easily calculated by subtracting stake from it.
        </p>

        <p class="text-gray-700 text-sm mt-3 font-semibold">
            Formula for profit:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            Profit &nbsp; = &nbsp; Payout &nbsp; - &nbsp; Stake
        </p>

    </x-auth-card>


    <script>
        // get DOM elements
        const button = document.getElementById('btn')
        const payout = document.getElementById('payout')
        const profit = document.getElementById('profit')
        const oddTypeError = document.getElementById('odd_type_error')
        const oddError = document.getElementById('odd_error')
        const stakeError = document.getElementById('stake_error')

        button.addEventListener('click', () => {
            // get inputs
            const oddType = document.getElementsByName('odd_type')
            const odd = document.getElementById('odd').value
            const stake = document.getElementById('stake').value

            // reset error fields
            oddTypeError.innerHTML = ''
            oddError.innerHTML = ''
            stakeError.innerHTML = ''

            // reset and hide payout and profit fields
            payout.innerHTML = ''
            payout.style.display = "none"
            profit.innerHTML = ''
            profit.style.display = "none"

            // check if any input field is empty
            let emptyField = false

            if (!oddType[0].checked && !oddType[1].checked) {

                oddTypeError.innerHTML = 'The odd type field is required'
                oddTypeError.style.display = "block"
                emptyField = true

            }

            if (!odd) {

                oddError.innerHTML = 'The odd field is required'
                oddError.style.display = "block"
                emptyField = true

            }

            if (!stake) {

                stakeError.innerHTML = 'The stake field is required'
                stakeError.style.display = "block"
                emptyField = true

            }

            if (!emptyField) {

                // if stake is in american format
                if (oddType[0].checked) {

                    // convert to decimal
                    const decimalOdd = americanToDecimal(odd).toFixed(3)

                    // calculations
                    const payoutCalc = decimalOdd * stake
                    const profitCalc = payoutCalc - stake

                    // show results
                    payout.innerHTML = 'Payout: ' + payoutCalc.toFixed(2)
                    profit.innerHTML = 'Profit: ' + profitCalc.toFixed(2)

                    // if stake is in decimal format 
                } else if (oddType[1].checked) {

                    // calculations
                    const payoutCalc = odd * stake
                    const profitCalc = payoutCalc - stake

                    // set results body
                    payout.innerHTML = 'Payout: ' + payoutCalc.toFixed(2)
                    profit.innerHTML = 'Profit: ' + profitCalc.toFixed(2)

                }

                // show results
                payout.style.display = "block"
                profit.style.display = "block"

            }

        })

        function americanToDecimal(odd) {
            if (odd > 0) {
                return (odd / 100) + 1
            } else {
                return (100 / Math.abs(odd)) + 1
            }
        }
    </script>
</x-app-layout>

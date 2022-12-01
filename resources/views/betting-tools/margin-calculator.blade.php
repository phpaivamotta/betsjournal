<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Margin Calculator') }}
        </h2>
    </x-slot>

    <x-auth-card class="sm:max-w-lg">

        <p class="text-xl font-semibold">Margin Calculator</p>

        <p class="font-medium text-sm text-gray-700">
            Calculate a bookmaker's margin.
        </p>

        <!-- Odds Format -->
        <p class="mt-5 mb-1 font-medium text-sm">
            Odds Format:
        </p>

        <div class="flex items-center">

            <input class="mr-1" type="radio" name="odd_type" id="american" value="american" required>
            <x-input-label for="american" :value="__('American')" />

            <input class="ml-4 mr-1" type="radio" name="odd_type" id="decimal" value="decimal">
            <x-input-label for="decimal" :value="__('Decimal')" />

        </div>

        {{-- oddtype error --}}
        <span class="block mt-1 text-red-500 text-xs" id="odd_type_error" style="display: none;"></span>

        <!-- Market Type -->
        <p class="mt-5 mb-1 font-medium text-sm">
            Market Type:
        </p>

        <div class="flex items-center">

            <input class="mr-1" type="radio" name="market_type" id="2-way" value="2-way" onclick="hideOddC()">
            <x-input-label for="2-way" :value="__('2-Way')" />

            <input class="ml-4 mr-1" type="radio" name="market_type" id="3-way" value="3-way"
                onclick="showOddC()">
            <x-input-label for="3-way" :value="__('3-Way')" />

        </div>

        {{-- oddtype error --}}
        <span class="block mt-1 text-red-500 text-xs" id="market_type_error" style="display: none;"></span>


        <div class="space-y-2 mt-6">

            {{-- odd A --}}
            <x-input-label for="odd-a" :value="__('Odd A')" />
            <x-text-input id="odd-a" class="block mt-1 w-full" type="number" step="0.001" name="odd-a" />

            {{-- odd A error --}}
            <span class="block mt-1 text-red-500 text-xs" id="odd_a_error" style="display: none"></span>

            {{-- odd B --}}
            <x-input-label for="odd-b" :value="__('Odd B')" />
            <x-text-input id="odd-b" class="block mt-1 w-full" type="number" step="0.001" name="odd-b" />

            {{-- odd B error --}}
            <span class="block mt-1 text-red-500 text-xs" id="odd_b_error" style="display: none"></span>

            {{-- odd C --}}
            <div id="odd-c-div" style="display: none;">
                <x-input-label for="odd-c" :value="__('Odd C')" />
                <x-text-input id="odd-c" class="block mt-1 w-full" type="number" step="0.001" name="odd-c" />

                {{-- odd C error --}}
                <span class="block mt-1 text-red-500 text-xs" id="odd_c_error" style="display: none"></span>
            </div>

        </div>

        <x-primary-button type="button" class="mt-4" id="btn">Calculate</x-primary-button>

        {{-- Bookie Margin --}}
        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="bookie-margin" style="display: none"></span>

        {{-- Odd A --}}
        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="fair-odd-prob-a" style="display: none"></span>

        {{-- Odd B --}}
        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="fair-odd-prob-b" style="display: none"></span>

        {{-- Odd C --}}
        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="fair-odd-prob-c" style="display: none"></span>

        {{-- betting tool info --}}
        <div class="flex items-center space-x-2 text-lg border-gray-200 border-t-2 mt-6 pt-4">
            <x-info-svg />

            <p>Tool Info</p>
        </div>

        <p class="mt-3 text-gray-700 text-sm">
            Bookmaker's make their money by including a betting marging in the odds. By inflating the implied
            probability of an outcome, they decrease the odds, which means you make less money when you win. The margin
            is, in essence, the difference between the real probabilities of an event and the odds offered by the
            bookmaker.
        </p>

        <p class="mt-3 text-gray-700 text-sm">
            If you are serious about making a profit, you should keep an eye on the best odds and lowest margins offered
            by bookmaker's. Our <span class="italic">Margin Calculator</span> makes it easy to find out how much you're
            being charged! It also tells you how much the actual fair probability and odds of an event are.
        </p>

        <p class="mt-3 text-gray-700 text-sm">
            In order to calculate the margin, the given odds should first be converted into implied probabilities. Once
            the event probabilities are known, then we can sum them to get the total - which, in a fair market, would be
            100%. Bookmaker's odds, however, usually sum up to 105%, the 5% is their "juice". The margins can vary from
            bookmaker to bookmaker, however. With the lowest margings being around 2% and the highest margins reaching
            10% or
            above.
        </p>

        <p class="mt-3 text-gray-700 text-sm">
            For instance, imagine the moneyline between Manchester City and Liverpool is listed in a bookmaker as 1.9 x
            1.9. This means that both teams have equal chances of winning, according to this bookmaker. Theoretically,
            if two outcomes have the same probability of occurring, then both teams have a 50% chance - which adds up to
            100%. If we convert the given odds into a probabiliy however, we'll see that the implied probabilities are
            actually 52.63%. This means that the bookies are actually charging a 5.26% margin on this line.
        </p>

        {{-- formulas --}}
        <p class="text-gray-700 text-sm mt-5">
            The following formula can be used to calculate the margin of a 2-way market, for instance.
        </p>

        <p
            class="bg-gray-700 mt-3 overflow-auto px-3 py-4 rounded-sm text-center text-gray-200 text-sm whitespace-nowrap mb-3">
            Margin &nbsp; = &nbsp; ( (1 / decimal odds A) &nbsp; + &nbsp; (1 / decimal odds B) &nbsp;
            - &nbsp; 1 ) &nbsp; x &nbsp; 100%
        </p>

    </x-auth-card>


    <script>
        // get DOM elements
        const button = document.getElementById('btn')
        const oddCDiv = document.getElementById('odd-c-div')
        const oddTypeError = document.getElementById('odd_type_error')
        const marketTypeError = document.getElementById('market_type_error')
        const oddAError = document.getElementById('odd_a_error')
        const oddBError = document.getElementById('odd_b_error')
        const oddCError = document.getElementById('odd_c_error')
        const bookieMargin = document.getElementById('bookie-margin')
        const fairOddProbA = document.getElementById('fair-odd-prob-a')
        const fairOddProbB = document.getElementById('fair-odd-prob-b')
        const fairOddProbC = document.getElementById('fair-odd-prob-c')

        button.addEventListener('click', () => {
            // get inputs
            const oddType = document.getElementsByName('odd_type')
            const marketType = document.getElementsByName('market_type')
            const oddA = document.getElementById('odd-a').value
            const oddB = document.getElementById('odd-b').value
            const oddC = document.getElementById('odd-c').value

            // reset error fields
            oddTypeError.innerHTML = ''
            marketTypeError.innerHTML = ''
            oddAError.innerHTML = ''
            oddBError.innerHTML = ''
            oddCError.innerHTML = ''

            // reset and hide result fields
            bookieMargin.innerHTML = ''
            bookieMargin.style.display = "none"
            fairOddProbA.innerHTML = ''
            fairOddProbA.style.display = "none"
            fairOddProbB.innerHTML = ''
            fairOddProbB.style.display = "none"
            fairOddProbC.innerHTML = ''
            fairOddProbC.style.display = "none"

            // check if any input field is empty
            let emptyField = false

            if (!oddType[0].checked && !oddType[1].checked) {

                oddTypeError.innerHTML = 'The odd type field is required'
                oddTypeError.style.display = "block"
                emptyField = true

            }

            if (!marketType[0].checked && !marketType[1].checked) {

                marketTypeError.innerHTML = 'The market type field is required'
                marketTypeError.style.display = "block"
                emptyField = true

            }

            if (!oddA) {

                oddAError.innerHTML = 'The odd a field is required'
                oddAError.style.display = "block"
                emptyField = true

            }

            if (!oddB) {

                oddBError.innerHTML = 'The odd b field is required'
                oddBError.style.display = "block"
                emptyField = true

            }

            if (marketType[1].checked && !oddC) {

                oddCError.innerHTML = 'The odd c field is required'
                oddCError.style.display = "block"
                emptyField = true

            }

            if (!emptyField) {

                // if stake is in american format
                if (oddType[0].checked) {

                    // divide margin by 2 or 3 depending on market type
                    if (marketType[0].checked) {
                        // convert to decimal
                        const oddADecimal = americanToDecimal(oddA).toFixed(3)
                        const oddBDecimal = americanToDecimal(oddB).toFixed(3)

                        // convert odds to probabilities
                        const probA = 1 / oddADecimal
                        const probB = 1 / oddBDecimal

                        // convert odds into decimal probabilities
                        const probSum = probA + probB

                        // subtract probabilities from 1 (100%) to -- get margin
                        let marginBookie = probSum - 1

                        const marginEachProb = marginBookie / 2

                        marginBookie = marginBookie * 100

                        // subtract divided margin from probabilities to -- get fair probabilities
                        let probFairA = probA - marginEachProb
                        let probFairB = probB - marginEachProb

                        // turn fair probabilities into -- fair odds
                        let oddFairA = 1 / probFairA
                        let oddFairB = 1 / probFairB

                        // convert to american format
                        oddFairA = decimalToAmerican(oddFairA)
                        oddFairB = decimalToAmerican(oddFairB)

                        // turn into probability
                        probFairA = probFairA * 100
                        probFairB = probFairB * 100

                        // set inner HTML with results
                        bookieMargin.innerHTML = 'Bookie Margin: ' + marginBookie.toFixed(2) + '%'
                        fairOddProbA.innerHTML = 'Odd A Fair: ' + oddFairA.toFixed(2) + ' (' + probFairA.toFixed(
                            2) + '%)'
                        fairOddProbB.innerHTML = 'Odd B Fair: ' + oddFairB.toFixed(2) + ' (' + probFairB.toFixed(
                            2) + '%)'

                    } else if (marketType[1].checked) {
                        // convert to decimal
                        const oddADecimal = americanToDecimal(oddA)
                        const oddBDecimal = americanToDecimal(oddB)
                        const oddCDecimal = americanToDecimal(oddC)

                        // convert odds into decimal probabilities
                        const probA = 1 / oddADecimal
                        const probB = 1 / oddBDecimal
                        const probC = 1 / oddCDecimal

                        // convert odds into decimal probabilities
                        const probSum = probA + probB + probC

                        // subtract probabilities from 1 (100%) to -- get margin
                        let marginBookie = probSum - 1

                        const marginEachProb = marginBookie / 3

                        marginBookie = marginBookie * 100

                        let probFairA = probA - marginEachProb
                        let probFairB = probB - marginEachProb
                        let probFairC = probC - marginEachProb

                        let oddFairA = 1 / probFairA
                        let oddFairB = 1 / probFairB
                        let oddFairC = 1 / probFairC

                        // convert to american format
                        oddFairA = decimalToAmerican(oddFairA)
                        oddFairB = decimalToAmerican(oddFairB)
                        oddFairC = decimalToAmerican(oddFairC)

                        probFairA = probFairA * 100
                        probFairB = probFairB * 100
                        probFairC = probFairC * 100

                        // set inner HTML with results
                        bookieMargin.innerHTML = 'Bookie Margin: ' + marginBookie.toFixed(2) + '%'
                        fairOddProbA.innerHTML = 'Fair Odd A: ' + oddFairA.toFixed(2) + ' (' + probFairA.toFixed(
                            2) + '%)'
                        fairOddProbB.innerHTML = 'Fair Odd B: ' + oddFairB.toFixed(2) + ' (' + probFairB.toFixed(
                            2) + '%)'
                        fairOddProbC.innerHTML = 'Fair Odd C: ' + oddFairC.toFixed(2) + ' (' + probFairC.toFixed(
                            2) + '%)'

                    }

                    // if stake is in decimal format 
                } else if (oddType[1].checked) {

                    // divide margin by 2 or 3 depending on market type
                    if (marketType[0].checked) {
                        // convert odds to probabilities
                        const probA = 1 / oddA
                        const probB = 1 / oddB

                        // convert odds into decimal probabilities
                        const probSum = probA + probB

                        // subtract probabilities from 1 (100%) to -- get margin
                        let marginBookie = probSum - 1

                        const marginEachProb = marginBookie / 2

                        marginBookie = marginBookie * 100

                        // subtract divided margin from probabilities to -- get fair probabilities
                        let probFairA = probA - marginEachProb
                        let probFairB = probB - marginEachProb

                        // turn fair probabilities into -- fair odds
                        const oddFairA = 1 / probFairA
                        const oddFairB = 1 / probFairB

                        // turn into probability
                        probFairA = probFairA * 100
                        probFairB = probFairB * 100

                        // set inner HTML with results
                        bookieMargin.innerHTML = 'Bookie Margin: ' + marginBookie.toFixed(2) + '%'
                        fairOddProbA.innerHTML = 'Fair Odd A: ' + oddFairA.toFixed(2) + ' (' + probFairA.toFixed(
                            2) + '%)'
                        fairOddProbB.innerHTML = 'Fair Odd B: ' + oddFairB.toFixed(2) + ' (' + probFairB.toFixed(
                            2) + '%)'

                    } else if (marketType[1].checked) {
                        // convert odds into decimal probabilities
                        const probA = 1 / oddA
                        const probB = 1 / oddB
                        const probC = 1 / oddC

                        // convert odds into decimal probabilities
                        const probSum = probA + probB + probC

                        // subtract probabilities from 1 (100%) to -- get margin
                        let marginBookie = probSum - 1

                        const marginEachProb = marginBookie / 3

                        marginBookie = marginBookie * 100

                        let probFairA = probA - marginEachProb
                        let probFairB = probB - marginEachProb
                        let probFairC = probC - marginEachProb

                        const oddFairA = 1 / probFairA
                        const oddFairB = 1 / probFairB
                        const oddFairC = 1 / probFairC

                        probFairA = probFairA * 100
                        probFairB = probFairB * 100
                        probFairC = probFairC * 100

                        // set inner HTML with results
                        bookieMargin.innerHTML = 'Bookie Margin: ' + marginBookie.toFixed(2) + '%'
                        fairOddProbA.innerHTML = 'Fair Odd A: ' + oddFairA.toFixed(2) + ' (' + probFairA.toFixed(
                            2) + '%)'
                        fairOddProbB.innerHTML = 'Fair Odd B: ' + oddFairB.toFixed(2) + ' (' + probFairB.toFixed(
                            2) + '%)'
                        fairOddProbC.innerHTML = 'Fair Odd C: ' + oddFairC.toFixed(2) + ' (' + probFairC.toFixed(
                            2) + '%)'

                    }

                }

                // show results
                bookieMargin.style.display = "block"
                fairOddProbA.style.display = "block"
                fairOddProbB.style.display = "block"

                if (marketType[1].checked) {
                    fairOddProbC.style.display = "block"
                }

            }

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

        function showOddC() {
            oddCDiv.style.display = "block"
        }

        function hideOddC() {
            oddCDiv.style.display = "none"
        }
    </script>
</x-app-layout>

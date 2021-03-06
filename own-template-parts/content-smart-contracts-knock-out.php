<?php

/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

?>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/f2103e8f69.js"></script>

<?php get_template_part('template-parts/entry-header'); ?>

<div class="post-inner">

	<a href="https://stockvoting.net/faq/#smart-contracts">
		<img data-toggle="tooltip" data-placement="top" title="Ask me" class="helping-ape font-tooltip" src="https://stockvoting.net/wp-content/uploads/2020/05/helping_ape.png">
	</a>

	<div class="entry-content entry-smart-contract">
		<div class="has-text-align-center">
			<!-- Explanation -->
			<h3 class="h2-highlighting-button" id="voting">What is this?</h3>
			<p>
				Chose your contract and buy certificate shares.<br>
				Like a <a href="https://www.investopedia.com/terms/k/knock-outoption.asp" target="_blank">Traditional Knock Out Certificate</a> you can bet against the others.<br>
				Make sure you watch how this works on blockchain:
			</p>
			<iframe src="https://www.youtube.com/embed/Pk5yofQiljo" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>

		<!-- Overview contracts table -->
		<figure class="wp-block-table alignwide is-style-stripes">
			<table class="table table-striped has-subtle-pale-blue-background-color has-background sv-table table-hover">

				<thead class="thead-dark">
					<tr>
						<th class="has-text-align-center" data-align="center">Type</th>
						<th class="has-text-align-center" data-align="center">Underlying</th>
						<th class="has-text-align-center" data-align="center">Threshold</th>
						<th class="has-text-align-center" data-align="center">Leverage</th>
						<th class="has-text-align-center" data-align="center">Pot [gwei] </th>
						<th class="has-text-align-center" data-align="center">Emission date</th>
						<th class="has-text-align-center" data-align="center">Due Date</th>
						<th class="has-text-align-center" data-align="center">Details</th>
					</tr>
				</thead>

				<!-- Iterate over all votings -->
				<?php
				foreach (getAllKO() as $KO_contract) {
				?>
					<tr class="table-row" data-target="#actionContractUser" data-val="<?php echo $KO_contract["contract_address"] ?>" data-toggle="modal">
						<td class="has-text-align-center" data-align="center"><?php echo display_string($KO_contract["typ"]) ?></td>
						<td class="has-text-align-center" data-align="center">
							<a href="<?php echo get_symbol_link($KO_contract["underlying"]) ?>" target="_blank">
								<?php echo getStockName($KO_contract["underlying"]) ?>
							</a>
						</td>
						<td class="has-text-align-center" data-align="center"><?php echo $KO_contract["threshold"] ?></td>
						<td class="has-text-align-center" data-align="center"><?php echo $KO_contract["leverage"] ?></td>
						<!-- todo: display closest currency -->
						<td class="has-text-align-center" data-align="center"><?php echo round(($KO_contract["pot"] / 1000000000)) ?></td>
						<td class="has-text-align-center" data-align="center"><?php echo $KO_contract["emissionDate"] ?></td>
						<td class="has-text-align-center" data-align="center"><?php echo $KO_contract["dueDate"] ?></td>
						<td class="has-text-align-center has-accent-color" data-align="center">
							<a href="<?php echo get_ropsten_link($KO_contract["contract_address"]) ?>" target="_blank">
								Here
							</a>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</figure>

		<!-- Button to add new contract -->
		<div class="form-group text-center">
			<button type="button" class="btn btn-primary smart-contract-button btn-lg" id="createNewContractButton"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create new Knock Out</button>
		</div>

		<!-- Input contract data div -->
		<div id="InputContractDataDiv" style="display: none;">
			<h2 class="own-h2 has-text-align-center has-accent-color" style="font-size:35px">New contract creation</h2>

			<form id="createKOContract_form_id">
				<div class="form-group">
					<label for="stockPickSelect">Type</label>
					<select multiple class="form-control" style="font-size:large;" id="typeSelect" required='required'>
						<option>Call</option>
						<option>Put</option>
					</select>

					<label for="stockPickSelect">Underlying (use Ctrl+F to search)</label>
					<select multiple class="form-control" style="font-size:large;" id="stockPickSelect" required='required'>
						<!-- todo: StockName, but get it later in ajax?-> bad, better in save as key TSLA -->
						<?php foreach (getAllSymbols(true) as $symbolArray) {	?>
							<option value="<?php echo $symbolArray["SymbolName"] ?>"><?php echo $symbolArray["StockName"] ?></option>
						<?php } ?>
					</select>

					<label>Threshold</label>
					<input type="number" class="form-control" id="threshold" placeholder="Place your threshold" required='required'>

					<label>Leverage</label>
					<input type="number" class="form-control" id="leverage" placeholder="Place your leverage">

					<label>Pot</label>
					<div class="input-group">
						<input type="number" class="form-control" id="potValueID" placeholder="Put money where your mouth is ;)" required='required'>
						<div class="input-group-append">
							<select class="selectpicker" id="pot_currency">
								<option selected value="finney">mETH</option>
								<option value="ether">ETH</option>
								<option value="gwei">gwei</option>
								<option value="wei">wei</option>
								<!-- todo: btc/usd, use etherscan.io for this -->
								<!-- <option value="btc">BTC</option> -->
								<!-- <option value="usd">$</option> -->
							</select>
						</div>
					</div>

					<label>Due Date</label>
					<input class="form-control" type="date" value="2020-08-19" id="ko_due_date" required='required'>
				</div>
				<!-- Create it! button -->
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary smart-contract-button btn-lg" onclick="createKOContract(this,typeSelect.value, stockPickSelect.value, threshold.value, leverage.value, potValueID.value, pot_currency.value, ko_due_date.value);" id="CreatItButton">Create it!</button>
				</div>
			</form>
		</div>

		<!-- ProgressDiv (...creation in progress...) -->
		<!-- todo: rename to progressingContractDiv -->
		<div id="loadingContractDiv" style="display: none;">
			<h2 class="own-h2 has-text-align-center">Loading/Creating contract...</h2>
			<div class="text-center">
				<div class="spinner-grow" role="status">
					<span class="sr-only text-primary">Loading...</span>
				</div>
			</div>
		</div>

		<!-- Successfully loaded -->
		<div id="ContractLoadingResultDiv" style="display: none;">
			<h2 class="own-h2 has-text-align-center has-accent-color" style="font-size:35px">Contract successfully created:</h2>

			<a href="" target="_blank" id="contractMinedHashLink">
				<h2 class="own-h2 has-text-align-center has-accent-color" id="contractMinedHash"></h2>
			</a>
		</div>

		<!-- Modal/Popup for Buying Shares -->
		<div class="modal fade" id="actionContractUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<!-- Header -->
					<div class="modal-header">
						<h5 class="modal-title text-left" id="exampleModalLabel">Buy Shares</h5>
					</div>

					<!-- Body -->
					<div class="modal-body">
						<h2 class="own-h2 has-text-align-center" id="contractAddress">Contract address</h2>
						<a href="https://ropsten.etherscan.io/address/" target="_blank" id="modal_contractAddressLink">
							<h2 class="own-h2 has-text-align-center has-accent-color" id="modal_contractAddress">0x123</h2>
						</a>
						<form>
							<div class="form-group">
								<label for="exampleInputEmail1">Amount (wei):</label>
								<!-- todo: check for minmum  -->
								<input type="email" class="form-control" id="buyingAmount" aria-describedby="emailHelp" placeholder="100000 wei">
							</div>
						</form>
					</div>

					<!-- Footer group -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" onclick="buyShares(modal_contractAddress.textContent,buyingAmount.value)" data-dismiss="modal" class="btn btn-primary">Buy</button>
					</div>
				</div>
			</div>
		</div>

		<?php get_template_part('own-parts/install-cryptoWallet'); ?>

	</div><!-- .entry-content -->
</div><!-- .post-inner -->

<script src="https://rawgit.com/ethereum/web3.js/0.16.0/dist/web3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ethjs@0.3.0/dist/ethjs.min.js"></script>
<script>
	// Currency Change
	var previous_currency;
	$("#pot_currency").on('focus', function(e) {
		previous_currency = $(this).val();
	});
	$('#pot_currency').on('change', function() {
		var currency = $(this).val();
		potValue = $('#potValueID')[0].value;

		var converted_value = changeCurrency(potValue, previous_currency, currency);

		// Change input value
		var element = document.getElementById('potValueID');
		element.value = converted_value;
		// Workaround because onfocus doesn't work
		previous_currency = currency;
	});

	$(function() {
		$('[data-toggle="tooltip"]').tooltip()
	})

	// Prevent default behaviour of submit button 
	$(document).ready(function() {
		// Listen to click event on the submit button
		$('#createKOContract_form_id').click(function(e) {
			if (document.getElementById("createKOContract_form_id").checkValidity()) {
				e.preventDefault();
			}

		});
	});

	// Logic for Web3 API
	window.addEventListener('load', function() {
		// Check if Web3 has been injected by the browser:
		if (typeof web3 !== 'undefined') {
			// You have a web3 browser! Continue below!
			initialization(web3, "knock-out");
		}
	})
</script>
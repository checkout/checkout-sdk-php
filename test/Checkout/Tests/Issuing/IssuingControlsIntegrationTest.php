<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Controls\ControlType;
use Checkout\Issuing\Controls\Query\CardControlsQuery;
use Checkout\Issuing\Controls\Update\UpdateCardControlRequest;
use Checkout\Issuing\Controls\VelocityLimit;
use Checkout\Issuing\Controls\VelocityWindow;
use Checkout\Issuing\Controls\VelocityWindowType;

class IssuingControlsIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $cardholder;
    private $card;
    private $control;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        $this->markTestSkipped("Avoid creating cards all the time");

        $this->before();
        $this->cardholder = $this->createCardholder();
        $this->card = $this->createCard($this->cardholder["id"], true);
        $this->control = $this->createCardControl($this->card["id"]);
    }

    /**
     * @test
     */
    public function shouldCreateControl()
    {
        $control = $this->control;

        $this->assertResponse(
            $control,
            "id",
            "description",
            "control_type",
            "target_id"
        );
        $this->assertEquals($this->card["id"], $control["target_id"]);
        $this->assertEquals(ControlType::$velocity_limit, $control["control_type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardControls()
    {
        $query = new CardControlsQuery();
        $query->target_id = $this->card["id"];

        $controls = $this->issuingApi->getIssuingClient()->getCardControls($query);

        $this->assertResponse(
            $controls,
            "controls"
        );
        foreach ($controls["controls"] as $control) {
            $this->assertEquals($this->card["id"], $control["target_id"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetControlDetails()
    {
        $control = $this->issuingApi->getIssuingClient()->getCardControlDetails($this->control["id"]);

        $this->assertResponse(
            $control,
            "id",
            "description",
            "control_type",
            "target_id"
        );
        $this->assertEquals($this->control["id"], $control["id"]);
        $this->assertEquals($this->card["id"], $control["target_id"]);
        $this->assertEquals(ControlType::$velocity_limit, $control["control_type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateControl()
    {
        $windowType = new VelocityWindow();
        $windowType->type = VelocityWindowType::$monthly;

        $velocityLimit = new VelocityLimit();
        $velocityLimit->amount_limit = 1000;
        $velocityLimit->velocity_window = $windowType;

        $updateRequest = new UpdateCardControlRequest();
        $updateRequest->description = "New max spend of 1000€ per month for restaurants";
        $updateRequest->velocity_limit = $velocityLimit;

        $control = $this->issuingApi->getIssuingClient()->updateCardControl($this->control["id"], $updateRequest);

        $this->assertResponse(
            $control,
            "id",
            "description",
            "control_type",
            "target_id"
        );
        $this->assertEquals($this->control["id"], $control["id"]);
        $this->assertEquals($this->card["id"], $control["target_id"]);
        $this->assertEquals(ControlType::$velocity_limit, $control["control_type"]);
        $this->assertEquals("New max spend of 1000€ per month for restaurants", $control["description"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRemoveControl()
    {
        $control = $this->issuingApi->getIssuingClient()->removeCardControl($this->control["id"]);

        $this->assertResponse(
            $control,
            "id"
        );
        $this->assertEquals($this->control["id"], $control["id"]);
    }
}

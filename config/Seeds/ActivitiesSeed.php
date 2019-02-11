<?php
use Migrations\AbstractSeed;

/**
 * Activities seed.
 */
class ActivitiesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    [
                        'name' => "Yes, I would like to sign up for the FREE Season Ticket Waitlist.",
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ],
                    [
                        'name' => "Yes, I would like to sign up for the FREE Redskins Women's club.",
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ],
                    [
                        'name' => "Yes, I would like to receive special offers from the Redskins and her Partner*.",
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ],
                    [
                        'name' => "Yes, I would like to sign up for the FREE Redskins Salute Military Appreciation Club.",
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ],
                ];

        $table = $this->table('activities');
        $table->insert($data)->save();
    }
}

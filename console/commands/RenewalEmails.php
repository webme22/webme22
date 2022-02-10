<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
include_once(__DIR__."/../../settings.php");
include_once(__DIR__."/../../lib/Mailer.php");
class RenewalEmails extends Command
{
	protected $commandName = 'mail:renewals';
	protected $commandDescription = "Send Plan about to expire emails";

	protected function configure()
	{
		$this
				->setName($this->commandName)
				->setDescription($this->commandDescription);
	}
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		global $siteUrl, $RELATIVE_PATH;
		$mailer = new Mailer();
		$families = Family::whereHas('all_plans', function (Illuminate\Database\Eloquent\Builder $q) {
			$q->whereDate('family_plans.end_date', '>=', date('Y-m-d', strtotime('-1 days')))
			->whereDate('family_plans.end_date', '<=', date('Y-m-d', strtotime('+29 days')));
		})->get();
		foreach($families as $family){
			$plan = $family->last_plan;
			$remaining_days = -2;
			if ($plan){
				$date1 = new DateTime("now");
				$date2 = new DateTime($plan->pivot['end_date']);
				$interval = $date1->diff($date2);
				$remaining_days = $interval->days;
				if($date1 > $date2){
					$remaining_days = $remaining_days * -1;
				}
			}
			$url = $siteUrl.$RELATIVE_PATH."renew_plan.php";
			if($remaining_days == 28 || $remaining_days == 21 || $remaining_days == 14 || $remaining_days == 7 || $remaining_days == 0){
				$mailer->setVars(['user_name'=>$family->creator->name, 'url'=>$url]);
				$emailSent = $mailer->sendMail([$family->creator->email], "Your plan is about to expire",
						'plan_about_to_expire.html', 'plan_about_to_expire.txt');
				SiteMail::create([
						'family_id' => $family->id,
						'name' => $family->creator->name,
						'email' => $family->creator->email,
						'title' => 'Plan About To Expire',
						'sent' => $emailSent,
						'date' => date('Y-m-d h:i:s a'),
				]);
			}
			else if ($remaining_days == -1){
				$mailer->setVars(['user_name'=>$family->creator->name, 'url'=>$url]);
				$emailSent = $mailer->sendMail([$family->creator->email], "Plan expired",
						'plan_expired.html', 'plan_expired.txt');
				SiteMail::create([
						'family_id' => $family->id,
						'name' => $family->creator->name,
						'email' => $family->creator->email,
						'title' => 'Plan Expired',
						'sent' => $emailSent,
						'date' => date('Y-m-d h:i:s a'),
				]);
			}
		}
		return 0;
	}
}

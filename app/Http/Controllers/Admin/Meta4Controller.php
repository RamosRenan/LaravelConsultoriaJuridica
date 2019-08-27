<?php

namespace App\Http\Controllers\Admin;

use App\Models\Meta4\ActiveDependents;
use App\Models\Meta4\ActiveMembers;
use App\Models\Meta4\ActiveMembersCourse;
use App\Models\Meta4\ActiveMembersCourseInternal;
use App\Models\Meta4\ActiveMembersFunction;
use App\Models\Meta4\ActiveMembersInfo;
use App\Models\Meta4\ActiveMembersRole;
use App\Models\Meta4\ActiveMembersWorkplace;
use App\Models\Meta4\HRMembers;
use App\Models\Meta4\HRPrivateFunction;
use App\Models\Meta4\MembersAbsence;
use App\Models\Meta4\PrivateFunctions;
use App\Models\Meta4\RetiredDependents;
use App\Models\Meta4\RetiredMembers;
use App\Models\Meta4\Units;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Meta4Controller extends Controller
{
    public function index() {
        $field = 'nome';
        $value = '%BUDAL%';

        if ($field == 'rg') {
            $fieldToSearch1 = 'rg';
            $fieldToSearch2 = 'cbr_num_rg';
        } else {
            $fieldToSearch1 = 'nome';
            $fieldToSearch2 = 'std_n_first_name';
        }

#        $info1 = HRMembers::where($fieldToSearch1, 'like', $value)->distinct()->get()->toArray();
#        $info2 = ActiveMembers::where($fieldToSearch2, 'like', $value)->select('cbr_num_rg', 'std_id_hr', 'std_or_hr_period', 'std_id_sub_geo_div', 'std_n_sub_geo_div', 'std_n_work_unitbra')->distinct()->get()->toArray();
#        $info3 = ActiveMembersInfo::where($fieldToSearch2, 'like', $value)->select('cbr_num_rg', 'cpf', 'std_n_genderbra', 'std_email', 'cbr_name_mather', 'cbr_name_father', 'sbr_num_tit', 'sbr_zona', 'sbr_secao', 'std_id_geo_div')->distinct()->get()->toArray();

        dd(
            Units::where('descricao_unidade_org', 'like', 'MILITAR%')
                ->orWhere('descricao_local_trab', 'like', 'PM%')
                ->select('unidade_de_organograma', 'unidade_pai', 'descricao_unidade_org')->distinct()->get()->toArray(),

            //            PrivateFunctions::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            //            ActiveMembersFunction::where('std_id_hr', 22093517)->distinct()->get()->toArray(), ########
            
                        //erivelton
            #            ActiveDependents::where('rg', 84222089)->select('nome', 'sexo', 'data_nasc', 'parentesco', 'dt_ini', 'dt_fim')->distinct()->get()->toArray(),
            
                        // pai
            #            RetiredMembers::where('cbr_num_rg', 22093517)->select('std_id_hr', 'std_or_hr_period', 'dt_ini_rh', 'id_tipo_rh', 'n_tipo_rh', 'cbr_num_rg', 'nome', 'dt_nasc', 'sexo', 'cargo', 'posto', 'quadro')->distinct()->get()->toArray(),
            #            RetiredDependents::where('rg', 22093517)->select('rg', 'nome', 'sexo', 'data_nasc', 'parentesco', 'dt_ini', 'dt_fim')->distinct()->get()->toArray(),
            
                        // eu
            #            MembersAbsence::where('cbr_num_rg', 77582886)->select('std_n_job_code', 'std_id_work_unit', 'std_n_work_unit', 'sco_id_incidence', 'sco_nm_incidence', 'sco_units', 'sco_dt_start', 'sco_dt_end')->distinct()->get()->toArray(),
            

#            $info1,
#            $info2,
#            $info3,
            
            #            ActiveMembersCourse::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            #            ActiveMembersCourseInternal::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            #            ActiveMembersRole::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            #            ActiveMembersWorkplace::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            #            HRPrivateFunction::where('std_id_hr', 250699)->distinct()->get()->toArray(),
            
                        '---'
            /*
                        ActiveMembers::select(
                                'rg', 'nome', 'organismo', 'unidade_de_organograma', 'denominacao', 
                                'centro_de_trabalho', 'cargo', 'serie_classe', 'classe', 'data_admissao', 
                                'nascimento', 'sexo', 'quadro', 'bairro', 'id_regiao'
                            )
                            ->join('')
                            ->where('nome', 'like', '%GASPARINI%')
                            ->distinct()->get()->toArray()
            */
                    );
    }
}

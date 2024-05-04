<table>
    <tr>
        <td colspan="11" style="text-align: center; font-size: 24px">
            <b>Listado de partidas aplicadas</b>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="11" style="font-size: 16px">
            <b>Proyecto: {{ $project->code }} - {{ $project->title }} </b>
        </td>
    </tr>
</table>
@foreach ($project->departures()->get() as $departure)
    <table>
        <tr>
            <td colspan="11" style="text-align: left; font-style: italic;">
                <b>Partida: {{ $departure->code }} - {{ $departure->description }}</b>
            </td>
        </tr>
    </table>
    @if ($departure->status === '2' && $departure->visible === 1)
        @if ($departure->variants()->get()->count())
            <table style="border: 1px solid #000">
                <tr>
                    <td style="border: 1px solid #000"><b>Usuario</b></td>
                    <td style="border: 1px solid #000"><b>Tipo</b></td>
                    <td style="border: 1px solid #000"><b>Incluye</b></td>
                    <td style="border: 1px solid #000"><b>Descripción</b></td>
                    <td style="border: 1px solid #000"><b>Cantidad</b></td>
                    <td style="border: 1px solid #000"><b>Dimension</b></td>
                    <td style="border: 1px solid #000"><b>Precio unitario</b></td>
                    <td style="border: 1px solid #000"><b>Incluye IVA</b></td>
                    <td style="border: 1px solid #000"><b>Precio Total</b></td>
                    <td style="border: 1px solid #000"><b>Métodos de pago</b></td>
                    <td style="border: 1px solid #000"><b>Variables Suplementarias</b></td>
                </tr>
                @foreach ($departure->variants()->get() as $variant)
                    @php
                        $optionA = [];
                        $variables_simple_option = [];
                        $variables_multiple_option = [];
                        $variables_upload_information = [];
                        $html = '';
                        
                        $user = $variant->user()->first()->first_name . ' ' . $variant->user()->first()->last_name;
                        $type = $variant->type === '1' ? 'Original' : 'Alternativo';
                        $description = $variant->description !== null ? $variant->description : '';
                        $include = ($variant->includes === '1' ? 'Suministro' : $variant->includes === '2') ? 'Instalación' : 'Suministro + Instalación';
                        $iva = $variant->iva === 1 ? 'Sí' : 'No';
                        
                        // Suplementary Variables
                        $simple_option = json_decode($variant->simple_option);
                        $multiple_option = json_decode($variant->multiple_option);
                        $upload_information = json_decode($variant->upload_information);
                        // Simple Option
                        if (isset($simple_option)) {
                            foreach ($simple_option as $key => $value) {
                                array_push($variables_simple_option, ['description' => $value->description, 'option' => $value->option]);
                            }
                        
                            if (count($variables_simple_option)) {
                                $html .= '<div><ul>';
                                for ($i = 0; $i < count($variables_simple_option); $i++) {
                                    $html .= '<li><b>' . $variables_simple_option[$i]['description'] . '</b><br>' . $variables_simple_option[$i]['option'] . '</li>';
                                }
                                $html .= '</ul></div>';
                            }
                        }
                        
                        // Multiple Option
                        if (isset($multiple_option)) {
                            foreach ($multiple_option as $key => $value) {
                                if (count($variables_multiple_option)) {
                                    for ($i = 0; $i < count($variables_multiple_option); $i++) {
                                        if (!in_array($value->description, $variables_multiple_option[$i])) {
                                            array_push($variables_multiple_option, ['description' => $value->description]);
                                        }
                                    }
                                } else {
                                    array_push($variables_multiple_option, ['description' => $value->description]);
                                }
                            }
                            //array_push($optionA, ['description' => 'hola']);
                            if (count($variables_multiple_option)) {
                                for ($i = 0; $i < count($variables_multiple_option); $i++) {
                                    $options = [];
                                    foreach ($multiple_option as $key => $value) {
                                        if ($variables_multiple_option[$i]['description'] === $value->description) {
                                            $options[] = $value->option;
                                        }
                                    }
                                    $variables_multiple_option[$i]['options'] = $options;
                                }
                            }
                        
                            if (count($variables_multiple_option)) {
                                $html .= '<div><ul>';
                                for ($i = 0; $i < count($variables_multiple_option); $i++) {
                                    $html .= '<li><span><b>' . $variables_multiple_option[$i]['description'] . '</b></span><br>';
                        
                                    for ($j = 0; $j < count($variables_multiple_option[$i]['options']); $j++) {
                                        $html .= '<span> - ' . $variables_multiple_option[$i]['options'][$j] . '</span><br>';
                                    }
                                    $html .= '</li>';
                                }
                                $html .= '</ul></div>';
                            }
                        }
                        
                        // Upload Information
                        // if (isset($upload_information)) {
                        //     foreach ($upload_information as $key => $value) {
                        //         array_push($variables_upload_information, ['file' => $value->file]);
                        //     }
                        //     //dd($variables_upload_information);
                        
                        //     if (count($variables_upload_information)) {
                        //         $html .= '<br><div><span>Documentos suministrados por el postulante</span><br>';
                        //         for ($i = 0; $i < count($variables_upload_information); $i++) {
                        //             $html .= '<a href="' . asset('storage') . '/' . $variables_upload_information[$i]['file'] . '" target="_blank">' . explode('/', $variables_upload_information[$i]['file'])[1] . '</a><br>';
                        //         }
                        //         $html .= '</div>';
                        //     }
                        // }
                        //ddd($html);
                        
                    @endphp
                    <tr>
                        <td style="border: 1px solid #000">{{ $user }}</td>
                        <td style="border: 1px solid #000">{{ $type }}</td>
                        <td style="border: 1px solid #000">{{ $include }} </td>
                        <td style="border: 1px solid #000">{{ $description }} </td>
                        <td style="border: 1px solid #000; text-align: right;">
                            {{ number_format($variant->quantity, 2, ',', '.') }}
                        </td>
                        <td style="border: 1px solid #000; text-align: center;">
                            {{ $departure->dimensions }}</td>
                        <td style="border: 1px solid #000; text-align: right;">
                            {{ number_format($variant->price_unit, 2, ',', '.') }}
                        </td>
                        <td style="border: 1px solid #000; text-align: center;">{{ $iva }}
                        </td>
                        <td style="border: 1px solid #000; text-align: right;">
                            {{ number_format($variant->price_total, 2, ',', '.') }}
                        </td>
                        <td style="border: 1px solid #000; text-align: center;">
                            <ul>
                                @foreach ($variant->payment_methods()->get() as $payment_method)
                                    <li> - {{ $payment_method->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="border: 1px solid #000;">
                            {!! $html !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <table>
                <tbody>
                    <tr>
                        <td colspan="11" class="text-left">
                            No hay datos
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
        {{-- iba aqui lo de arriba --}}
    @endif
@endforeach
